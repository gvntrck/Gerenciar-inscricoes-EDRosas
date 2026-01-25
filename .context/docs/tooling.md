---
type: doc
name: tooling
description: Scripts, IDE settings, automation, and developer productivity tips
category: tooling
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Ferramentas de Desenvolvimento

## Ambiente de Desenvolvimento

### **Opções de Ambiente Local**

**Local by Flywheel** (Recomendado)
- Interface gráfica simples
- WordPress pré-configurado
- Suporte a múltiplos sites

**XAMPP**
- Apache + MySQL + PHP
- Configuração manual necessária
- Multiplataforma

**Docker**
- Ambiente isolado e reproduzível
- Requer conhecimento de containers
- Configuração via `docker-compose.yml`

## IDE e Editores

### **Visual Studio Code** (Recomendado)
Extensões úteis:
- **PHP Intelephense** - Autocomplete e análise PHP
- **WordPress Snippets** - Snippets WordPress
- **PHP Debug** - Debugging com Xdebug
- **EditorConfig** - Consistência de formatação

### **PhpStorm**
- IDE completa para PHP
- Integração WordPress nativa
- Debugging avançado
- Refactoring automático

## Configurações IDE

### **VSCode settings.json**
```json
{
  "php.suggest.basic": false,
  "intelephense.stubs": [
    "wordpress",
    "woocommerce"
  ],
  "files.associations": {
    "*.php": "php"
  },
  "editor.tabSize": 4,
  "editor.insertSpaces": true
}
```

### **EditorConfig**
```ini
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
trim_trailing_whitespace = true

[*.php]
indent_style = space
indent_size = 4
```

## Scripts Úteis

### **Verificação de Sintaxe**
```bash
# Verificar sintaxe de todos os arquivos PHP
find . -name "*.php" -exec php -l {} \;
```

### **Buscar TODOs**
```bash
# Encontrar comentários TODO no código
grep -r "TODO" --include="*.php" .
```

### **Limpar Cache WordPress**
```bash
# Via WP-CLI
wp cache flush
```

## Debugging

### **WordPress Debug Mode**
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### **Xdebug**
Configuração PHP:
```ini
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
```

### **Query Monitor Plugin**
- Monitora queries do banco
- Analisa performance
- Debug de hooks e filtros

## Ferramentas de Linha de Comando

### **WP-CLI**
Comandos úteis:
```bash
# Ativar plugin
wp plugin activate inscricoes-pagas

# Listar pedidos WooCommerce
wp wc order list

# Exportar banco de dados
wp db export
```

### **Composer** (Futuro)
Para gerenciamento de dependências PHP:
```json
{
  "require": {
    "php": ">=7.4"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.0"
  }
}
```

## Automação

### **Git Hooks**

**Pre-commit** (`.git/hooks/pre-commit`):
```bash
#!/bin/bash
# Verificar sintaxe PHP antes de commit
for file in $(git diff --cached --name-only --diff-filter=ACM | grep "\.php$"); do
    php -l "$file"
    if [ $? -ne 0 ]; then
        echo "Erro de sintaxe em $file"
        exit 1
    fi
done
```

### **Tarefas Futuras**
- CI/CD com GitHub Actions
- Testes automatizados em cada push
- Deploy automático para staging

## Produtividade

### **Snippets Úteis**

**Criar classe WordPress**:
```php
namespace InscricoesPagas\Modulo;

if (!defined('ABSPATH')) {
    exit;
}

class Nome_Classe
{
    public function __construct()
    {
        // Constructor
    }
}
```

**Handler AJAX**:
```php
public function handle(): void
{
    $this->verify_request();
    
    $data = sanitize_text_field($_POST['data'] ?? '');
    
    // Processar
    
    wp_send_json_success(['message' => 'Sucesso']);
}
```

### **Atalhos de Teclado**
- `Ctrl+P` - Buscar arquivo (VSCode)
- `Ctrl+Shift+F` - Buscar em todos os arquivos
- `F12` - Ir para definição
- `Ctrl+/` - Comentar linha

## Recursos Externos

- **WordPress Codex**: https://codex.wordpress.org/
- **WooCommerce Docs**: https://woocommerce.com/documentation/
- **PHP Manual**: https://www.php.net/manual/
- **WordPress Coding Standards**: https://developer.wordpress.org/coding-standards/

## Recursos Relacionados

- **Desenvolvimento**: `development-workflow.md`
- **Testes**: `testing-strategy.md`
