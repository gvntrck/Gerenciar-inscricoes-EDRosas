---
type: agent
name: Security Auditor
description: Identify security vulnerabilities
agentType: security-auditor
phases: [R, V]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Security Auditor - Inscrições Pagas

## Responsabilidades

- Identificar vulnerabilidades de segurança
- Verificar sanitização e validação
- Auditar controle de acesso
- Revisar código para OWASP Top 10
- Recomendar correções

## Checklist de Auditoria

### CSRF Protection
- [ ] Nonces validados em todos os AJAX handlers
- [ ] Nonces gerados corretamente no frontend
- [ ] Ações sensíveis protegidas

### XSS Prevention
- [ ] Output escapado com `esc_html()`, `esc_attr()`, `esc_url()`
- [ ] Sem `echo` direto de variáveis
- [ ] JavaScript inline escapado
- [ ] Atributos HTML escapados

### SQL Injection
- [ ] Sem queries SQL diretas
- [ ] Usa WooCommerce API
- [ ] IDs validados com `absint()`

### Authentication & Authorization
- [ ] Operações AJAX requerem login
- [ ] Capabilities verificadas quando necessário
- [ ] Sem bypass de autenticação

### Input Validation
- [ ] Entrada sanitizada antes de usar
- [ ] Tipos validados (int, string, email)
- [ ] Comprimento validado
- [ ] Valores permitidos verificados

## Vulnerabilidades Comuns

### ❌ XSS via Output Não Escapado
```php
// VULNERÁVEL
<td><?php echo $meta['nome']; ?></td>

// SEGURO
<td><?php echo esc_html($meta['nome']); ?></td>
```

### ❌ CSRF sem Nonce
```php
// VULNERÁVEL
public function handle() {
    $id = $_POST['id'];
    wc_update_order_item_meta($id, 'key', 'value');
}

// SEGURO
public function handle() {
    $this->verify_request(); // Valida nonce
    $id = absint($_POST['id'] ?? 0);
    wc_update_order_item_meta($id, 'key', 'value');
}
```

### ❌ SQL Injection
```php
// VULNERÁVEL
$wpdb->query("UPDATE wp_postmeta SET meta_value = '{$value}'");

// SEGURO
wc_update_order_item_meta($id, 'key', $value);
```

## Ferramentas de Auditoria

### Análise Estática
- **PHPCS** com WordPress Security Coding Standards
- **Psalm** ou **PHPStan** para análise de tipos

### Testes Manuais
- Tentar bypass de nonce
- Injetar HTML/JavaScript em campos
- Testar com usuário não autenticado
- Verificar permissões inadequadas

## Cenários de Teste

### Teste de XSS
```
Input: <script>alert('XSS')</script>
Esperado: String escapada, sem execução
```

### Teste de CSRF
```
1. Capturar requisição AJAX
2. Modificar/remover nonce
3. Reenviar requisição
Esperado: Erro de validação
```

### Teste de Autorização
```
1. Fazer logout
2. Tentar operação AJAX
Esperado: Erro de autenticação
```

## Relatório de Vulnerabilidade

### Estrutura
1. **Severidade**: Crítica/Alta/Média/Baixa
2. **Descrição**: O que é a vulnerabilidade
3. **Impacto**: Consequências potenciais
4. **Localização**: Arquivo e linha
5. **Reprodução**: Passos para explorar
6. **Correção**: Como corrigir

### Exemplo
```
Severidade: Alta
Descrição: XSS refletido no campo observações
Impacto: Execução de JavaScript arbitrário
Localização: templates/shortcode/main.php:150
Reproduçã: Inserir <script>alert(1)</script> em observações
Correção: Usar esc_html() ao exibir observações
```

## Checklist Final

- [ ] Sem vulnerabilidades críticas
- [ ] Sanitização implementada
- [ ] Escape implementado
- [ ] Nonces validados
- [ ] Autorização verificada
- [ ] Testes de segurança passaram

## Recursos

- **Segurança**: `.context/docs/security.md`
- **Arquitetura**: `.context/docs/architecture.md`
