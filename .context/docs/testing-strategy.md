---
type: doc
name: testing-strategy
description: Test frameworks, patterns, coverage requirements, and quality gates
category: testing
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Testing Strategy

Como a qualidade é mantida no plugin Inscrições Pagas.

## Test Types

### Testes Manuais (Atual)
- **Escopo**: Funcionalidades principais do plugin
- **Ambiente**: WordPress local com WooCommerce
- **Responsável**: Desenvolvedor

### Recomendações para Testes Automatizados

#### Unit Tests (Futuro)
- **Framework**: PHPUnit com WordPress Test Suite
- **Convenção**: `tests/unit/test-*.php`
- **Cobertura**: Classes helpers, validações

#### Integration Tests (Futuro)
- **Framework**: PHPUnit + WP Test Factory
- **Escopo**: Integração WooCommerce, AJAX handlers
- **Ambiente**: WordPress de teste

#### E2E Tests (Futuro)
- **Framework**: Playwright ou Cypress
- **Escopo**: Fluxos completos de inscrição
- **Ambiente**: Site WordPress staging

## Running Tests

### Testes Manuais
```bash
# Verificar ativação do plugin
wp plugin activate inscricoes-pagas

# Verificar se WooCommerce está ativo
wp plugin list | grep woocommerce

# Testar via navegador
# - Acessar página com shortcode
# - Testar filtros e ordenação
# - Testar exportação CSV
# - Testar edição de dados (admin)
```

### Setup PHPUnit (Quando Implementado)
```bash
# Instalar dependências
composer install

# Rodar testes
./vendor/bin/phpunit

# Cobertura
./vendor/bin/phpunit --coverage-html coverage/
```

## Quality Gates

### Requisitos Mínimos
- [ ] Plugin ativa sem erros
- [ ] Não há warnings PHP no debug log
- [ ] Funcionalidades core funcionam (listar, filtrar, exportar)
- [ ] AJAX handlers respondem corretamente
- [ ] Compatibilidade com WooCommerce verificada

### Linting & Formatting
```bash
# PHP CodeSniffer com WordPress Standards (quando configurado)
phpcs --standard=WordPress includes/

# Correção automática
phpcbf --standard=WordPress includes/
```

### Antes de Merge
1. Código testado localmente
2. Sem erros de PHP
3. Funcionalidade documentada
4. Versão atualizada se aplicável

## Troubleshooting

### Problemas Comuns

#### Plugin não ativa
- Verificar versão do PHP (7.4+)
- Verificar se WooCommerce está ativo
- Checar debug log

#### AJAX não funciona
- Verificar nonces
- Checar console do navegador
- Verificar hooks registrados

#### Tabela não exibe dados
- Verificar shortcode correto
- Checar queries no debug log
- Confirmar existência das tabelas

### Debug
```php
// Adicionar ao wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);
```

Consulte [Development Workflow](./development-workflow.md) para processos diários.
