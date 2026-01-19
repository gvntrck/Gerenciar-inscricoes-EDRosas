---
type: doc
name: security
description: Security policies, authentication, secrets management, and compliance requirements
category: security
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Security & Compliance Notes

Este documento captura políticas e diretrizes de segurança para o plugin Inscrições Pagas.

## Authentication & Authorization

### Sistema de Autenticação
- **Provider**: WordPress Core Authentication
- **Sessões**: Gerenciadas pelo WordPress via cookies
- **Tokens**: Nonces para proteção CSRF

### Modelo de Permissões
| Capability | Permissão |
|-----------|-----------|
| `manage_options` | Acesso total às configurações |
| `edit_posts` | Visualização básica |
| `administrator` | Todas as funcionalidades admin |

### Verificação de Permissões
```php
// Exemplo de verificação
if (!current_user_can('manage_options')) {
    wp_die(__('Acesso negado', 'inscricoes-pagas'));
}
```

## Secrets & Sensitive Data

### Armazenamento
- **Opções do Plugin**: Tabela `wp_options` (criptografada via WordPress)
- **Dados de Inscrição**: Tabelas customizadas com prefixo WordPress
- **Credenciais WooCommerce**: Gerenciadas pelo WooCommerce

### Dados Sensíveis
| Dado | Classificação | Tratamento |
|------|---------------|------------|
| Email | PII | Sanitização, escape |
| Nome | PII | Sanitização, escape |
| CPF | PII/Sensível | Validação, armazenamento seguro |
| Dados de Pagamento | Financeiro | Gerenciado pelo WooCommerce |

### Práticas de Segurança
1. **Sanitização de Input**:
   ```php
   $email = sanitize_email($_POST['email']);
   $name = sanitize_text_field($_POST['name']);
   ```

2. **Escape de Output**:
   ```php
   echo esc_html($user_data);
   echo esc_attr($attribute);
   ```

3. **Prepared Statements**:
   ```php
   $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d", $id);
   ```

4. **Nonce Verification**:
   ```php
   if (!wp_verify_nonce($_POST['nonce'], 'action_name')) {
       wp_die('Unauthorized');
   }
   ```

## Compliance & Policies

### LGPD (Lei Geral de Proteção de Dados)
- [ ] Consentimento para coleta de dados
- [ ] Possibilidade de exclusão de dados
- [ ] Transparência sobre uso de dados

### WordPress Security Guidelines
- [ ] Seguir WordPress Coding Standards
- [ ] Validar e sanitizar todos os inputs
- [ ] Escapar todos os outputs
- [ ] Usar nonces em forms e AJAX
- [ ] Verificar capabilities antes de ações

### Checklist de Segurança
- [ ] SQL Injection: Usar prepared statements
- [ ] XSS: Escapar outputs
- [ ] CSRF: Verificar nonces
- [ ] Auth: Verificar capabilities
- [ ] File Upload: Validar tipos e sanitizar nomes

## Incident Response

### Contatos de Emergência
- Administrador do site WordPress
- Suporte do provedor de hospedagem

### Passos de Resposta
1. Identificar e isolar o problema
2. Desativar plugin se necessário
3. Revisar logs de erro WordPress
4. Corrigir vulnerabilidade
5. Atualizar e comunicar stakeholders

Consulte [Architecture](./architecture.md) para contexto técnico.
