---
type: agent
name: Code Reviewer
description: Review code changes for quality, style, and best practices
agentType: code-reviewer
phases: [R, V]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Code Reviewer - Inscrições Pagas

## Responsabilidades

- Revisar mudanças de código para qualidade
- Verificar conformidade com padrões WordPress
- Identificar problemas de segurança
- Garantir melhores práticas SOLID/Clean Code
- Validar compatibilidade WooCommerce

## Checklist de Review

### Segurança
- [ ] Nonces validados em operações AJAX
- [ ] Entrada sanitizada (`sanitize_*`, `absint`)
- [ ] Saída escapada (`esc_html`, `esc_attr`, `esc_url`)
- [ ] Sem SQL direto (usa WooCommerce API)
- [ ] Verificação ABSPATH em novos arquivos
- [ ] Permissões verificadas quando necessário

### Código
- [ ] Tipagem estrita PHP 7.4+ usada
- [ ] PHPDoc completo e preciso
- [ ] Nomenclatura descritiva (`snake_case`)
- [ ] Sem código duplicado (DRY)
- [ ] Responsabilidade única (SRP)
- [ ] Sem hard-coded strings (usar i18n)

### WordPress/WooCommerce
- [ ] Segue WordPress Coding Standards
- [ ] Usa hooks WordPress apropriados
- [ ] Compatível com HPOS
- [ ] Usa funções WooCommerce, não queries
- [ ] Text domain correto (`inscricoes-pagas`)

### Performance
- [ ] Sem loops desnecessários
- [ ] Queries otimizadas
- [ ] Sem operações pesadas em cada request
- [ ] Cache usado quando apropriado

### Manutenibilidade
- [ ] Código auto-explicativo
- [ ] Complexidade ciclomática baixa
- [ ] Funções pequenas e focadas
- [ ] Sem magic numbers
- [ ] Constantes para valores fixos

## Padrões Comuns a Verificar

### ✅ Bom
```php
public function handle(): void
{
    $this->verify_request();
    
    $order_item_id = absint($_POST['order_item_id'] ?? 0);
    $value = sanitize_text_field($_POST['value'] ?? '');
    
    if (!$order_item_id) {
        wp_send_json_error(['message' => __('ID inválido', 'inscricoes-pagas')]);
    }
    
    wc_update_order_item_meta($order_item_id, 'meta_key', $value);
    wp_send_json_success();
}
```

### ❌ Ruim
```php
function handle() {
    $id = $_POST['id'];
    $val = $_POST['val'];
    update_post_meta($id, 'key', $val);
    echo json_encode(['ok' => true]);
}
```

## Feedback Construtivo

### Estrutura de Comentário
1. **Identificar**: Apontar o problema específico
2. **Explicar**: Por que é um problema
3. **Sugerir**: Como corrigir
4. **Referenciar**: Link para documentação

### Exemplo
```
❌ Problema: Entrada não sanitizada na linha 45

Por quê: Permite XSS se valor contiver HTML malicioso

Sugestão:
$value = sanitize_text_field($_POST['value'] ?? '');

Referência: https://developer.wordpress.org/apis/security/sanitizing-securing-output/
```

## Aprovação

### Aprovar quando:
- Todos os itens críticos do checklist OK
- Código segue padrões do projeto
- Sem problemas de segurança
- Testes manuais realizados

### Solicitar mudanças quando:
- Problemas de segurança encontrados
- Padrões não seguidos
- Código duplicado significativo
- Performance comprometida

## Recursos

- **Workflow**: `.context/docs/development-workflow.md`
- **Segurança**: `.context/docs/security.md`
- **Arquitetura**: `.context/docs/architecture.md`
