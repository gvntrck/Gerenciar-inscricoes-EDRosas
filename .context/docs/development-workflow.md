---
type: doc
name: development-workflow
description: Day-to-day engineering processes, branching, and contribution guidelines
category: workflow
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Development Workflow

Este documento descreve o processo de desenvolvimento diário para contribuição no plugin Inscrições Pagas.

## Branching & Releases

- **Branch principal**: `main` — código estável, pronto para produção
- **Feature branches**: `feature/nome-da-feature` — desenvolvimento de novas funcionalidades
- **Bugfix branches**: `fix/descricao-do-bug` — correções de bugs
- **Release tags**: `v2.0.0` — versionamento semântico (MAJOR.MINOR.PATCH)

### Fluxo Git
1. Criar branch a partir de `main`
2. Desenvolver e commitar frequentemente
3. Abrir Pull Request
4. Code review
5. Merge após aprovação

## Local Development

### Requisitos
- PHP 7.4+
- WordPress 5.0+ instalado localmente
- WooCommerce 4.0+ ativo
- Servidor local (XAMPP, WAMP, Local by Flywheel, etc.)

### Setup
```bash
# Clonar o repositório para a pasta de plugins
cd /wp-content/plugins/
git clone [repo-url] inscricoes-pagas

# Ativar o plugin via WP-CLI
wp plugin activate inscricoes-pagas

# Ou ativar manualmente via painel WordPress
```

### Desenvolvimento
- Edite arquivos PHP diretamente, o WordPress carrega automaticamente
- Para JavaScript, os arquivos em `assets/js/` são carregados via enqueue
- CSS em `assets/css/` também via enqueue WordPress

### Debugging
```php
// Habilitar debug no wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## Code Review Expectations

### Checklist de Review
- [ ] Código segue WordPress Coding Standards
- [ ] Novos hooks são documentados
- [ ] Strings são traduzíveis (`__()`, `_e()`)
- [ ] Sanitização de inputs (`sanitize_*()`)
- [ ] Escape de outputs (`esc_html()`, `esc_attr()`)
- [ ] Nonces validados em forms e AJAX
- [ ] Sem código comentado ou debug logs

### Requisitos de Aprovação
- Mínimo 1 aprovação para merge
- Testes locais confirmados
- Versão do plugin atualizada se necessário

Consulte [AGENTS.md](../../AGENTS.md) para dicas de colaboração com agentes AI.

## Onboarding Tasks

### Primeiros Passos
1. Leia o [Project Overview](./project-overview.md)
2. Entenda a [Arquitetura](./architecture.md)
3. Configure ambiente local
4. Explore a estrutura de arquivos
5. Faça uma pequena correção ou melhoria como primeiro PR

### Recursos Úteis
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WooCommerce Developer Docs](https://developer.woocommerce.com/)
- [Testing Strategy](./testing-strategy.md)
- [Tooling](./tooling.md)
