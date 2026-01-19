---
type: doc
name: tooling
description: Scripts, IDE settings, automation, and developer productivity tips
category: tooling
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Tooling & Productivity Guide

Ferramentas, automação e configurações para desenvolvimento eficiente no plugin Inscrições Pagas.

## Required Tooling

### Ambiente de Desenvolvimento
| Ferramenta | Versão | Propósito | Instalação |
|------------|--------|-----------|------------|
| PHP | 7.4+ | Runtime | Sistema operacional ou XAMPP/WAMP |
| WordPress | 5.0+ | Plataforma | Download oficial |
| WooCommerce | 4.0+ | E-commerce | Plugin WordPress |
| Servidor Web | Apache/Nginx | Servir aplicação | XAMPP, WAMP, Local |
| MySQL | 5.7+ | Banco de dados | Incluído no XAMPP |

### Ferramentas de Desenvolvimento
| Ferramenta | Propósito | Instalação |
|------------|-----------|------------|
| VS Code | Editor código | [code.visualstudio.com](https://code.visualstudio.com) |
| WP-CLI | CLI WordPress | `composer global require wp-cli/wp-cli-bundle` |
| Git | Controle versão | [git-scm.com](https://git-scm.com) |
| Composer | Dependências PHP | [getcomposer.org](https://getcomposer.org) (opcional) |

## Recommended Automation

### WP-CLI Commands
```bash
# Ativar/desativar plugin
wp plugin activate inscricoes-pagas
wp plugin deactivate inscricoes-pagas

# Limpar cache (se houver plugin de cache)
wp cache flush

# Verificar status do plugin
wp plugin list | grep inscricoes

# Debug mode
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw
```

### Git Hooks (Pre-commit)
```bash
#!/bin/sh
# .git/hooks/pre-commit

# Verificar sintaxe PHP
find includes/ -name "*.php" -exec php -l {} \;

# Checar código comentado de debug
if grep -r "error_log\|var_dump\|print_r" includes/; then
    echo "Debug code found!"
    exit 1
fi
```

## IDE / Editor Setup

### VS Code Extensions Recomendadas
- **PHP Intelephense** — IntelliSense PHP
- **WordPress Snippets** — Snippets WordPress
- **PHP Debug** — Debugging com Xdebug
- **EditorConfig** — Consistência de formatação
- **GitLens** — Git integrado

### Configurações VS Code (`.vscode/settings.json`)
```json
{
    "php.validate.executablePath": "C:/xampp/php/php.exe",
    "editor.formatOnSave": true,
    "editor.tabSize": 4,
    "editor.insertSpaces": false,
    "[php]": {
        "editor.defaultFormatter": "bmewburn.vscode-intelephense-client"
    }
}
```

### Snippets Úteis
```json
{
    "WordPress Action Hook": {
        "prefix": "wp-action",
        "body": [
            "add_action('${1:hook_name}', [$this, '${2:callback}'], ${3:10}, ${4:1});"
        ]
    },
    "WordPress Filter Hook": {
        "prefix": "wp-filter",
        "body": [
            "add_filter('${1:hook_name}', [$this, '${2:callback}'], ${3:10}, ${4:1});"
        ]
    },
    "AJAX Handler": {
        "prefix": "wp-ajax",
        "body": [
            "add_action('wp_ajax_${1:action}', [$this, '${2:callback}']);",
            "add_action('wp_ajax_nopriv_${1:action}', [$this, '${2:callback}']);"
        ]
    }
}
```

## Productivity Tips

### Aliases Úteis (PowerShell/Bash)
```bash
# Alias para diretório do plugin
alias cdip="cd /c/xampp/htdocs/wp-content/plugins/inscricoes-pagas"

# Alias para WP-CLI
alias wpa="wp plugin activate"
alias wpd="wp plugin deactivate"
```

### Local by Flywheel
Recomendado para desenvolvimento local WordPress:
- Fácil setup de múltiplos sites
- SSL local
- PHP version switching
- Mailhog para testes de email

### Xdebug Setup
```ini
; php.ini
[xdebug]
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_port=9003
```

Consulte [Development Workflow](./development-workflow.md) para processos de desenvolvimento.
