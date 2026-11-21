<?php
/**
 * Modelo Informe: consultas agregadas para los reportes de avance.
 * Todas aceptan un sprint (0 = todos los sprints).
 */
class Informe extends Model
{
    private function filtro(int $sprintId): array
    {
        return $sprintId > 0 ? [' WHERE sprint_id = ?', [$sprintId]] : ['', []];
    }

    /** Resumen: historias, puntos totales y puntos finalizados. */
    public function resumen(int $sprintId = 0): array
    {
        [$where, $par] = $this->filtro($sprintId);
        $st = $this->db->prepare(
            "SELECT COUNT(*) AS historias, COALESCE(SUM(puntos), 0) AS puntos,
                    COALESCE(SUM(CASE WHEN estado = 'finalizada' THEN puntos ELSE 0 END), 0) AS puntos_finalizados
             FROM historias" . $where
        );
        $st->execute($par);
        $f = array_map('intval', $st->fetch());
        $f['avance'] = porcentaje($f['puntos_finalizados'], $f['puntos']);
        return $f;
    }

    /** Historias y puntos por estado (incluye estados sin historias en 0). */
    public function porEstado(int $sprintId = 0): array
    {
        [$where, $par] = $this->filtro($sprintId);
        $st = $this->db->prepare('SELECT estado, COUNT(*) AS historias, COALESCE(SUM(puntos), 0) AS puntos FROM historias' . $where . ' GROUP BY estado');
        $st->execute($par);

        $r = [];
        foreach (Historia::ESTADOS as $e) {
            $r[$e] = ['historias' => 0, 'puntos' => 0];
        }
        foreach ($st->fetchAll() as $f) {
            $r[$f['estado']] = ['historias' => (int)$f['historias'], 'puntos' => (int)$f['puntos']];
        }
        return $r;
    }

    /** Carga de trabajo por responsable, con su detalle por estado. */
    public function porResponsable(int $sprintId = 0): array
    {
        [$where, $par] = $this->filtro($sprintId);
        $st = $this->db->prepare(
            "SELECT responsable,
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado = 'nueva' THEN 1 ELSE 0 END)       AS nuevas,
                    SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END)      AS activas,
                    SUM(CASE WHEN estado = 'finalizada' THEN 1 ELSE 0 END)  AS finalizadas,
                    SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END) AS impedimentos,
                    SUM(puntos) AS puntos,
                    SUM(CASE WHEN estado = 'finalizada' THEN puntos ELSE 0 END) AS puntos_finalizados
             FROM historias" . $where . ' GROUP BY responsable ORDER BY responsable'
        );
        $st->execute($par);
        return $st->fetchAll();
    }
}
