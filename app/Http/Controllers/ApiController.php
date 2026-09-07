<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Closure;

class ApiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                if (!\Illuminate\Support\Facades\Auth::check() && !session()->has('kalibrasi_user') && !session()->has('client_logged_in')) {
                    return response()->json(['error' => 'Akses ditolak. Silakan login terlebih dahulu.'], 401);
                }
                return $next($request);
            },
        ];
    }

    /**
     * Generic query handler to serve Supabase-like JS queries over MySQL
     */
    public function query(Request $request, string $table)
    {
        try {
            $allowedTables = [
                'master_emisi', 'coc_emisi', 'samples', 'master_peralatan',
                'surat_jalan_peralatan', 'client_accounts', 'client_messages',
                'sampling_requests', 'profiles', 'users', 'audit_logs'
            ];

            if (!in_array($table, $allowedTables)) {
                return response()->json(['error' => 'Table not allowed: ' . $table], 400);
            }

            $query = DB::table($table);

            if ($request->has('where')) {
                $wheres = json_decode($request->input('where'), true);
                if (is_array($wheres)) {
                    $allowedOps = ['=', '!=', '>', '<', '>=', '<=', 'like', 'in'];
                    foreach ($wheres as $w) {
                        if (isset($w['column'], $w['operator'], $w['value']) && Schema::hasColumn($table, $w['column'])) {
                            $op = strtolower($w['operator']);
                            if (in_array($op, $allowedOps)) {
                                if ($op === 'in') {
                                    $query->whereIn($w['column'], (array)$w['value']);
                                } else {
                                    $query->where($w['column'], $w['operator'], $w['value']);
                                }
                            }
                        }
                    }
                }
            }

            if ($request->has('like')) {
                $likes = json_decode($request->input('like'), true);
                if (is_array($likes)) {
                    foreach ($likes as $l) {
                        if (isset($l['column'], $l['value']) && Schema::hasColumn($table, $l['column'])) {
                            $query->where($l['column'], 'like', $l['value']);
                        }
                    }
                }
            }

            if ($request->has('order')) {
                $orders = json_decode($request->input('order'), true);
                if (is_array($orders)) {
                    foreach ($orders as $o) {
                        if (isset($o['column']) && Schema::hasColumn($table, $o['column'])) {
                            $dir = strtolower($o['direction'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
                            $query->orderBy($o['column'], $dir);
                        } elseif (Schema::hasColumn($table, 'id')) {
                            $query->orderBy('id', 'desc');
                        }
                    }
                }
            } else {
                if (Schema::hasColumn($table, 'id')) {
                    $query->orderBy('id', 'desc');
                }
            }

            if ($request->has('limit')) {
                $userLimit = (int)$request->input('limit');
                $query->limit(min(max(1, $userLimit), 1000));
            } else {
                $query->limit(500);
            }

            $hiddenFields = ['password', 'remember_token', 'token'];

            $data = $query->get()->map(function ($item) use ($table, $hiddenFields) {
                foreach ($hiddenFields as $hf) {
                    if (isset($item->$hf)) {
                        unset($item->$hf);
                    }
                }

                foreach ($item as $k => $v) {
                    if (is_string($v) && (str_starts_with($v, '{') || str_starts_with($v, '['))) {
                        $decoded = json_decode($v, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $item->$k = $decoded;
                        }
                    }
                }

                if ($table === 'coc_emisi') {
                    $samples = DB::table('samples')->where('coc_id', $item->id)->get()->map(function ($s) use ($hiddenFields) {
                        foreach ($s as $sk => $sv) {
                            if (is_string($sv) && (str_starts_with($sv, '{') || str_starts_with($sv, '['))) {
                                $decodedS = json_decode($sv, true);
                                if (json_last_error() === JSON_ERROR_NONE) {
                                    $s->$sk = $decodedS;
                                }
                            }
                        }
                        return $s;
                    });

                    if ($samples->count() > 0) {
                        $item->samples = $samples;
                        if (empty($item->samples_data)) {
                            $item->samples_data = $samples;
                        }
                    }
                }

                return $item;
            });

            if ($table === 'samples' && $data->count() > 0) {
                $cocIds = $data->pluck('coc_id')->filter()->unique();
                if ($cocIds->count() > 0) {
                    $cocs = DB::table('coc_emisi')->whereIn('id', $cocIds)->get()->keyBy('id');
                    foreach ($data as $item) {
                        if (isset($item->coc_id) && isset($cocs[$item->coc_id])) {
                            $coc = $cocs[$item->coc_id];
                            foreach ($coc as $ck => $cv) {
                                if (is_string($cv) && (str_starts_with($cv, '{') || str_starts_with($cv, '['))) {
                                    $decodedC = json_decode($cv, true);
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        $coc->$ck = $decodedC;
                                    }
                                }
                            }
                            $item->coc_emisi = $coc;
                        }
                    }
                }
            }

            if ($request->has('single') && $request->boolean('single')) {
                return response()->json(['data' => $data->first(), 'error' => null]);
            }

            return response()->json(['data' => $data, 'error' => null]);
        } catch (\Throwable $e) {
            return response()->json(['data' => [], 'error' => $e->getMessage()], 200);
        }
    }

    public function insert(Request $request, string $table)
    {
        $allowedWriteTables = [
            'master_emisi', 'coc_emisi', 'samples', 'master_peralatan',
            'surat_jalan_peralatan', 'client_accounts', 'client_messages',
            'sampling_requests', 'profiles'
        ];

        if (!in_array($table, $allowedWriteTables)) {
            return response()->json(['error' => 'Table not allowed for insert: ' . $table], 400);
        }

        $payload = $request->json()->all();
        $records = isset($payload[0]) && is_array($payload[0]) ? $payload : [$payload];
        $validColsFlip = array_flip(Schema::getColumnListing($table));

        $inserted = [];
        foreach ($records as $data) {
            if ($table === 'master_emisi' && !isset($data['nama_parameter']) && isset($data['parameter'])) {
                $data['nama_parameter'] = $data['parameter'];
            }
            foreach ($data as $k => $v) {
                if (is_array($v) || is_object($v)) {
                    $data[$k] = json_encode($v);
                }
            }
            $data['created_at'] = now();
            $data['updated_at'] = now();
            $filteredData = array_intersect_key($data, $validColsFlip);

            $id = DB::table($table)->insertGetId($filteredData);
            $inserted[] = DB::table($table)->where('id', $id)->first();
        }

        return response()->json(['data' => count($inserted) === 1 ? $inserted[0] : $inserted, 'error' => null]);
    }

    public function update(Request $request, string $table)
    {
        $allowedWriteTables = [
            'master_emisi', 'coc_emisi', 'samples', 'master_peralatan',
            'surat_jalan_peralatan', 'client_accounts', 'client_messages',
            'sampling_requests', 'profiles'
        ];

        if (!in_array($table, $allowedWriteTables)) {
            return response()->json(['error' => 'Table not allowed for update: ' . $table], 400);
        }

        $payload = $request->json()->all();
        $whereColumn = $request->input('_match_col', 'id');
        $whereValue = $request->input('_match_val');

        if (is_null($whereValue) || $whereValue === '') {
            return response()->json(['error' => 'Parameter _match_val wajib diisi.'], 400);
        }

        if (!Schema::hasColumn($table, $whereColumn)) {
            return response()->json(['error' => 'Invalid match column: ' . $whereColumn], 400);
        }

        $validColsFlip = array_flip(Schema::getColumnListing($table));

        $data = $payload['data'] ?? $payload;
        unset($data['_match_col'], $data['_match_val']);

        foreach ($data as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $data[$k] = json_encode($v);
            }
        }
        $data['updated_at'] = now();
        $filteredData = array_intersect_key($data, $validColsFlip);

        DB::table($table)->where($whereColumn, $whereValue)->update($filteredData);
        $updated = DB::table($table)->where($whereColumn, $whereValue)->first();

        return response()->json(['data' => $updated, 'error' => null]);
    }

    public function upsert(Request $request, string $table)
    {
        $allowedWriteTables = [
            'master_emisi', 'coc_emisi', 'samples', 'master_peralatan',
            'surat_jalan_peralatan', 'client_accounts', 'client_messages',
            'sampling_requests', 'profiles'
        ];

        if (!in_array($table, $allowedWriteTables)) {
            return response()->json(['error' => 'Table not allowed for upsert: ' . $table], 400);
        }

        try {
            $payload = $request->json()->all();
            $data = $payload['data'] ?? $payload;
            $onConflict = $payload['onConflict'] ?? 'id';

            $records = isset($data[0]) && is_array($data[0]) ? $data : [$data];
            $matchCols = array_map('trim', explode(',', $onConflict));
            $validColsFlip = array_flip(Schema::getColumnListing($table));

            $resultRecords = [];
            foreach ($records as $row) {
                if ($table === 'master_emisi' && !isset($row['nama_parameter']) && isset($row['parameter'])) {
                    $row['nama_parameter'] = $row['parameter'];
                }

                foreach ($row as $k => $v) {
                    if (is_array($v) || is_object($v)) {
                        $row[$k] = json_encode($v);
                    }
                }
                $row['updated_at'] = now();

                $filteredRow = array_intersect_key($row, $validColsFlip);

                $query = DB::table($table);
                $hasCondition = false;
                foreach ($matchCols as $col) {
                    if (isset($filteredRow[$col]) && Schema::hasColumn($table, $col)) {
                        $query->where($col, $filteredRow[$col]);
                        $hasCondition = true;
                    }
                }

                if ($hasCondition && (clone $query)->exists()) {
                    (clone $query)->update($filteredRow);
                    $resultRecords[] = (clone $query)->first();
                } else {
                    $filteredRow['created_at'] = now();
                    $id = DB::table($table)->insertGetId($filteredRow);
                    $resultRecords[] = DB::table($table)->where('id', $id)->first();
                }
            }

            $resData = is_array($data) && isset($data[0]) ? $resultRecords : ($resultRecords[0] ?? true);
            return response()->json(['data' => $resData, 'error' => null]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ApiController Upsert Exception: ' . $e->getMessage(), [
                'table' => $table,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan internal saat menyimpan/memperbarui data.';
            return response()->json(['data' => null, 'error' => ['message' => $message]], 500);
        }
    }

    public function delete(Request $request, string $table)
    {
        $allowedWriteTables = [
            'master_emisi', 'coc_emisi', 'samples', 'master_peralatan',
            'surat_jalan_peralatan', 'client_accounts', 'client_messages',
            'sampling_requests', 'profiles'
        ];

        if (!in_array($table, $allowedWriteTables)) {
            return response()->json(['error' => 'Table not allowed for delete: ' . $table], 400);
        }

        $whereColumn = $request->input('_match_col', 'id');
        $whereValue = $request->input('_match_val');

        if (is_null($whereValue) || $whereValue === '') {
            return response()->json(['error' => 'Parameter _match_val wajib diisi.'], 400);
        }

        if (!Schema::hasColumn($table, $whereColumn)) {
            return response()->json(['error' => 'Invalid match column: ' . $whereColumn], 400);
        }

        DB::table($table)->where($whereColumn, $whereValue)->delete();
        return response()->json(['data' => true, 'error' => null]);
    }
}
