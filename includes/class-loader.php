<?php
/**
 * Classe Loader - Gerenciador de Hooks.
 *
 * Mantém uma lista de todos os hooks registrados pelo plugin e os executa
 * quando o método run() é chamado. Isso permite um melhor controle e
 * organização dos hooks.
 *
 * @package InscricoesPagas
 * @since   2.0.0
 */

namespace InscricoesPagas;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Loader.
 *
 * @since 2.0.0
 */
class Loader
{
    /**
     * Array de actions registradas.
     *
     * @var array
     */
    protected array $actions = [];

    /**
     * Array de filters registrados.
     *
     * @var array
     */
    protected array $filters = [];

    /**
     * Adiciona uma nova action ao array de actions.
     *
     * @param string $hook          Nome do hook WordPress.
     * @param object $component     Instância do objeto que contém o callback.
     * @param string $callback      Nome do método a ser chamado.
     * @param int    $priority      Prioridade do hook.
     * @param int    $accepted_args Número de argumentos aceitos.
     */
    public function add_action(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Adiciona um novo filter ao array de filters.
     *
     * @param string $hook          Nome do hook WordPress.
     * @param object $component     Instância do objeto que contém o callback.
     * @param string $callback      Nome do método a ser chamado.
     * @param int    $priority      Prioridade do hook.
     * @param int    $accepted_args Número de argumentos aceitos.
     */
    public function add_filter(
        string $hook,
        object $component,
        string $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Método utilitário para adicionar hooks ao array correspondente.
     *
     * @param array  $hooks         Array de hooks (actions ou filters).
     * @param string $hook          Nome do hook WordPress.
     * @param object $component     Instância do objeto que contém o callback.
     * @param string $callback      Nome do método a ser chamado.
     * @param int    $priority      Prioridade do hook.
     * @param int    $accepted_args Número de argumentos aceitos.
     *
     * @return array Array de hooks atualizado.
     */
    private function add(
        array $hooks,
        string $hook,
        object $component,
        string $callback,
        int $priority,
        int $accepted_args
    ): array {
        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        ];

        return $hooks;
    }

    /**
     * Registra todos os hooks com o WordPress.
     */
    public function run(): void
    {
        // Registrar filters
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        // Registrar actions
        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [$hook['component'], $hook['callback']],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
