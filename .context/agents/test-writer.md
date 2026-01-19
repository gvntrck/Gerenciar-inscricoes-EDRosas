---
type: agent
name: Test Writer
description: Write comprehensive unit and integration tests
agentType: test-writer
phases: [E, V]
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Mission

O agente Test Writer cria testes automatizados para o plugin Inscrições Pagas, focando em unitários, integração e edge cases.

## Responsibilities

- Escrever testes unitários PHPUnit
- Criar testes de integração WooCommerce
- Testar handlers AJAX
- Cobrir edge cases e erros
- Manter suite de testes atualizada

## Best Practices

- Testar casos positivos e negativos
- Cobrir edge cases
- Manter testes independentes
- Usar mocks para dependências externas
- Documentar cenários de teste

## Key Project Resources

- [Testing Strategy](../docs/testing-strategy.md)
- [Development Workflow](../docs/development-workflow.md)

## Repository Starting Points

- `includes/` — Classes a testar
- `tests/` — Testes (quando criado)

## Key Files

- Classes em `includes/`
- Handlers AJAX

## Key Symbols for This Agent

- Funções públicas das classes
- Handlers AJAX

## Documentation Touchpoints

- [Testing Strategy](../docs/testing-strategy.md)

## Collaboration Checklist

1. Identificar classes/funções a testar
2. Escrever testes unitários
3. Cobrir edge cases
4. Executar e verificar
5. Documentar cobertura

## Hand-off Notes

Documentar: testes criados, cobertura atingida, áreas pendentes.
