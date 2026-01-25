---
type: doc
name: security
description: Security policies, authentication, secrets management, and compliance requirements
category: security
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Segurança

## Políticas de Segurança

O plugin **Inscrições Pagas** implementa práticas de segurança WordPress padrão para proteger contra vulnerabilidades comuns como XSS, CSRF, SQL Injection e acesso não autorizado.

## Autenticação e Autorização

### **Controle de Acesso**
- **Operações AJAX**: Requerem usuário logado no WordPress
- **Capabilities**: Usa sistema de permissões WordPress (`current_user_can`)
- **Nonces**: Todas as requisições AJAX validam nonce antes de processar

### **Validação de Nonces**
```php
// Geração (JavaScript)
nonce: ipData.nonce

// Validação (PHP)
check_ajax_referer('ip_nonce', 'nonce');
```

## Proteção contra Vulnerabilidades

### **Cross-Site Scripting (XSS)**
- **Output Escaping**: Todos os dados exibidos usam `esc_html()`, `esc_attr()`, `esc_url()`
- **Template Rendering**: Escape adequado em `templates/shortcode/main.php`

### **Cross-Site Request Forgery (CSRF)**
- **Nonces WordPress**: Validados em todos os handlers AJAX
- **Verificação**: `check_ajax_referer()` antes de qualquer operação

### **SQL Injection**
- **WooCommerce API**: Usa prepared statements internamente
- **Sem queries diretas**: Plugin não executa SQL diretamente
- **Sanitização**: IDs numéricos validados com `absint()`

### **Sanitização de Entrada**
```php
$order_item_id = absint($_POST['order_item_id'] ?? 0);
$value = sanitize_text_field($_POST['value'] ?? '');
$meta_key = sanitize_key($_POST['meta_key'] ?? '');
```

## Gerenciamento de Secrets

### **Não há secrets no plugin**
- Plugin não usa API keys externas
- Não armazena credenciais
- Integração nativa com WordPress/WooCommerce

### **Dados Sensíveis**
- **Emails e telefones**: Armazenados em metadados WooCommerce
- **Proteção**: Mesma segurança do banco WordPress
- **Acesso**: Apenas usuários autenticados via painel WordPress

## Requisitos de Compliance

### **LGPD/GDPR**
- Plugin não coleta dados diretamente
- Dados são coletados via WooCommerce
- Responsabilidade de compliance é do site WordPress

### **Retenção de Dados**
- Dados persistem enquanto pedido WooCommerce existir
- Exclusão: Via painel WooCommerce (deleta pedidos)
- Exportação: CSV disponível para portabilidade

## Práticas de Código Seguro

### **Verificações de Segurança**
1. **Verificar ABSPATH**: Todos os arquivos PHP verificam `defined('ABSPATH')`
2. **Validar entrada**: Sanitização antes de usar dados de usuário
3. **Escapar saída**: Escape antes de renderizar HTML
4. **Verificar permissões**: Validar capabilities WordPress
5. **Usar nonces**: Proteger formulários e AJAX

### **Exemplo de Handler AJAX Seguro**
```php
public function handle(): void
{
    // 1. Verificar nonce
    $this->verify_request();
    
    // 2. Sanitizar entrada
    $order_item_id = absint($_POST['order_item_id'] ?? 0);
    $value = sanitize_text_field($_POST['value'] ?? '');
    
    // 3. Validar dados
    if (!$order_item_id) {
        wp_send_json_error(['message' => 'ID inválido']);
    }
    
    // 4. Executar operação
    wc_update_order_item_meta($order_item_id, 'meta_key', $value);
    
    // 5. Retornar resposta
    wp_send_json_success(['message' => 'Atualizado']);
}
```

## Auditoria e Logging

### **Logging Atual**
- Não há sistema de logging implementado
- Erros PHP logados via `WP_DEBUG_LOG`
- Operações AJAX retornam sucesso/erro em JSON

### **Recomendações Futuras**
- Implementar log de alterações em metadados críticos
- Registrar tentativas de acesso não autorizado
- Monitorar exportações CSV para auditoria

## Atualizações de Segurança

### **Processo**
1. Monitorar vulnerabilidades WordPress/WooCommerce
2. Atualizar dependências quando necessário
3. Testar compatibilidade antes de deploy
4. Incrementar versão do plugin

### **Dependências**
- **WordPress**: Manter atualizado (5.0+ requerido)
- **WooCommerce**: Manter atualizado (4.0+ requerido)
- **PHP**: Manter atualizado (7.4+ requerido)

## Checklist de Segurança

- [x] Nonces validados em operações AJAX
- [x] Sanitização de entrada implementada
- [x] Escape de saída implementado
- [x] Verificação de permissões WordPress
- [x] Sem SQL direto (usa WooCommerce API)
- [x] Verificação ABSPATH em todos os arquivos
- [ ] Sistema de logging de auditoria (futuro)
- [ ] Rate limiting em AJAX (futuro)
- [ ] Testes de segurança automatizados (futuro)

## Recursos Relacionados

- **Arquitetura**: `architecture.md`
- **Fluxo de Dados**: `data-flow.md`
