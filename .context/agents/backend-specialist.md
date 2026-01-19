---
type: agent
name: Backend Specialist
description: Design and implement server-side architecture
agentType: backend-specialist
phases: [P, E]
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Mission

O agente Backend Specialist é responsável por projetar e implementar a arquitetura server-side do plugin Inscrições Pagas. Este agente é acionado quando há necessidade de desenvolver novas funcionalidades PHP, otimizar queries de banco de dados, criar integrações com WooCommerce ou implementar handlers AJAX.

## Responsibilities

- Desenvolver classes PHP seguindo PSR-4 e WordPress Coding Standards
- Implementar handlers AJAX para operações assíncronas
- Criar e otimizar queries SQL usando `$wpdb` com prepared statements
- Desenvolver integrações com WooCommerce hooks e filters
- Implementar lógica de ativação/desativação do plugin
- Garantir sanitização de inputs e escape de outputs
- Criar tabelas customizadas no banco de dados
- Implementar exportação de dados (CSV)

## Best Practices

- Usar `$wpdb->prepare()` para todas as queries SQL
- Implementar nonces para proteção CSRF em AJAX
- Verificar `current_user_can()` antes de ações privilegiadas
- Usar WordPress transients para cache quando apropriado
- Documentar funções com PHPDoc comments
- Seguir princípios SOLID e Clean Code
- Usar DRY - não repetir código, criar helpers
- Sanitizar com `sanitize_*()` e escapar com `esc_*()`

## Key Project Resources

- [Documentation Index](../docs/README.md)
- [AGENTS.md](../../AGENTS.md)
- [Architecture](../docs/architecture.md)
- [Data Flow](../docs/data-flow.md)

## Repository Starting Points

- `includes/` — Código PHP principal do plugin
- `includes/Ajax/` — Handlers AJAX (6 arquivos)
- `includes/Helpers/` — Classes utilitárias
- `includes/Admin/` — Funcionalidades administrativas
- `includes/Public/` — Funcionalidades públicas

## Key Files

- [`inscricoes-pagas.php`](../../inscricoes-pagas.php) — Entry point, autoloader
- [`includes/class-plugin.php`](../../includes/class-plugin.php) — Classe principal
- [`includes/class-loader.php`](../../includes/class-loader.php) — Registro de hooks
- [`includes/class-activator.php`](../../includes/class-activator.php) — Lógica de ativação
- [`includes/Helpers/class-csv-exporter.php`](../../includes/Helpers/class-csv-exporter.php) — Exportação CSV

## Key Symbols for This Agent

- `InscricoesPagas\Plugin` — Classe orquestradora principal
- `InscricoesPagas\Loader` — Registro de actions e filters
- `InscricoesPagas\Activator` — Setup de tabelas e opções
- Handlers AJAX em `includes/Ajax/`

## Documentation Touchpoints

- [Architecture](../docs/architecture.md) — Estrutura do sistema
- [Data Flow](../docs/data-flow.md) — Fluxo de dados
- [Security](../docs/security.md) — Políticas de segurança
- [Development Workflow](../docs/development-workflow.md) — Processos

## Collaboration Checklist

1. Confirmar requisitos e escopo da funcionalidade
2. Revisar arquitetura existente antes de implementar
3. Implementar seguindo WordPress Coding Standards
4. Testar funcionalidade localmente
5. Verificar segurança (nonces, capabilities, sanitização)
6. Atualizar versão do plugin se necessário
7. Documentar nova funcionalidade
8. Criar PR para review

## Hand-off Notes

Ao completar uma tarefa, documentar:
- Arquivos modificados/criados
- Novos hooks disponíveis
- Dependências adicionadas
- Testes realizados
- Próximos passos recomendados
