<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $historias = new Historia();
        $totales   = $historias->totales();

        $this->render('home/index', [
            'titulo'       => 'Panel principal',
            'totales'      => $totales,
            'avance'       => porcentaje($totales['puntos_finalizados'], $totales['puntos']),
            'sprints'      => (new Sprint())->conMetricas(),
            'impedimentos' => $historias->listar(0, 'impedimento'),
        ]);
    }
}
