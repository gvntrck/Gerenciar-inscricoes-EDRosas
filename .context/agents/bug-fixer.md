---
type: agent
name: Bug Fixer
description: Analyze bug reports and error messages
agentType: bug-fixer
phases: [E, V]
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Mission

O agente Bug Fixer analisa relatórios de bugs, mensagens de erro e comportamentos inesperados no plugin Inscrições Pagas. Este agente foca em identificar a causa raiz, implementar correções mínimas e prevenir regressões.

## Responsibilities

- Analisar mensagens de erro PHP e JavaScript
- Identificar causa raiz de problemas
- Implementar correções com mínimo impacto
- Verificar se correção não causa regressões
- Testar em diferentes cenários
- Documentar bug e correção aplicada
- Atualizar versão do plugin (PATCH)
- Sugerir melhorias preventivas

## Best Practices

- Reproduzir o bug antes de corrigir
- Entender completamente o fluxo afetado
- Fazer correções cirúrgicas, não refatorar
- Testar cenário original e casos relacionados
- Verificar compatibilidade com WooCommerce
- Checar logs de debug para mais contexto
- Documentar o que causou o bug
- Incrementar versão PATCH (x.x.X)

## Key Project Resources

- [Architecture](../docs/architecture.md) — Entender estrutura
- [Data Flow](../docs/data-flow.md) — Fluxo de dados
- [Security](../docs/security.md) — Verificar vulnerabilidades
- [Testing Strategy](../docs/testing-strategy.md) — Como testar

## Repository Starting Points

- `includes/` — Código PHP principal
- `includes/Ajax/` — Problemas em AJAX
- `assets/js/` — Bugs JavaScript
- `templates/` — Problemas de renderização

## Key Files

- [`inscricoes-pagas.php`](../../inscricoes-pagas.php) — Verificar inicialização
- `includes/class-plugin.php` — Hooks e inicialização
- `assets/js/inscricoes-pagas.js` — JavaScript principal
- Debug log: `wp-content/debug.log`

## Key Symbols for This Agent

- Handlers AJAX em `includes/Ajax/`
- Funções públicas em `includes/Public/`
- Scripts JS em `assets/js/`
- Helpers em `includes/Helpers/`

## Documentation Touchpoints

- [Architecture](../docs/architecture.md) — Estrutura do sistema
- [Data Flow](../docs/data-flow.md) — Como dados fluem
- [Tooling](../docs/tooling.md) — Ferramentas de debug

## Collaboration Checklist

1. Reproduzir o bug localmente
2. Coletar informações (versão PHP, WP, WC)
3. Analisar logs de erro
4. Identificar causa raiz
5. Implementar correção mínima
6. Testar correção
7. Verificar não há regressões
8. Atualizar versão PATCH
9. Documentar correção

## Hand-off Notes

Ao corrigir um bug:
- Descrever sintoma original
- Explicar causa raiz encontrada
- Listar arquivos modificados
- Detalhar correção aplicada
- Confirmar testes realizados
- Sugerir melhorias preventivas (se houver)
