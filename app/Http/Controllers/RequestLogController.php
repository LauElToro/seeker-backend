<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *   name="Request Logs",
 *   description="Auditoría de solicitudes que exceden el umbral de 500 metros"
 * )
 */
class RequestLogController extends Controller
{
    /**
     * Listar logs con filtros y paginación.
     *
     * @OA\Get(
     *   path="/api/request-logs",
     *   summary="Ver logs de requests (opcionalmente, sólo los que superan 500m)",
     *   tags={"Request Logs"},
     *   @OA\Parameter(
     *     name="start_date", in="query", required=false, description="Desde (YYYY-MM-DD)",
     *     @OA\Schema(type="string", format="date", example="2025-07-01")
     *   ),
     *   @OA\Parameter(
     *     name="end_date", in="query", required=false, description="Hasta (YYYY-MM-DD)",
     *     @OA\Schema(type="string", format="date", example="2025-07-31")
     *   ),
     *   @OA\Parameter(
     *     name="exceeds_500", in="query", required=false, description="Filtrar sólo > 500m (true/false)",
     *     @OA\Schema(type="boolean", example=true)
     *   ),
     *   @OA\Parameter(
     *     name="page", in="query", required=false, description="Página", @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Parameter(
     *     name="per_page", in="query", required=false, description="Resultados por página (máx 100)",
     *     @OA\Schema(type="integer", example=10)
     *   ),
     *   @OA\Response(
     *     response=200, description="Listado paginado",
     *     @OA\JsonContent(
     *       @OA\Property(property="data", type="array",
     *         @OA\Items(
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="latitude", type="number", example=-34.9),
     *           @OA\Property(property="longitude", type="number", example=-58.9),
     *           @OA\Property(property="distance_meters", type="integer", example=1200),
     *           @OA\Property(property="created_at", type="string", example="2025-07-29T18:40:41.000000Z")
     *         )
     *       ),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta", type="object")
     *     )
     *   )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date'],
            'exceeds_500' => ['nullable', 'boolean'],
            'page'        => ['nullable', 'integer', 'min:1'],
            'per_page'    => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 10);

        $query = RequestLog::query();

        if (!empty($validated['start_date'])) {
            $query->whereDate('created_at', '>=', $validated['start_date']);
        }
        if (!empty($validated['end_date'])) {
            $query->whereDate('created_at', '<=', $validated['end_date']);
        }
        if (array_key_exists('exceeds_500', $validated)) {
            if ((bool) $validated['exceeds_500'] === true) {
                $query->where('distance_meters', '>', 500);
            }
        }

        $logs = $query->orderByDesc('created_at')->paginate($perPage);

        return response()->json($logs);
    }
}
