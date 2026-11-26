<?php
class InformeController extends Controller
{
    public function index(): void
    {
        $sprintId = (int)($_GET['sprint'] ?? 0);
        $informe  = new Informe();

        $this->render('informes/index', [
            'titulo'        => 'Informes',
            'sprints'       => (new Sprint())->todos(),
            'sprintId'      => $sprintId,
            'resumen'       => $informe->resumen($sprintId),
            'porEstado'     => $informe->porEstado($sprintId),
            'porResponsable'=> $informe->porResponsable($sprintId),
        ]);
    }
}
