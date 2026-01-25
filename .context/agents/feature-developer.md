---
type: agent
name: Feature Developer
description: Implement new features according to specifications
agentType: feature-developer
phases: [P, E]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Feature Developer - Inscrições Pagas

## Responsabilidades

- Implementar novas funcionalidades conforme especificação
- Seguir arquitetura e padrões existentes
- Integrar com WooCommerce de forma segura
- Documentar código e atualizar versão
- Testar funcionalidade completa

## Workflow de Desenvolvimento

### 1. Planejar
- Entender requisitos completamente
- Identificar arquivos a modificar
- Planejar integração com código existente
- Considerar casos extremos

### 2. Implementar Backend
- Criar/modificar classes em `includes/`
- Adicionar handlers AJAX se necessário
- Atualizar `Meta_Extractor` para novos campos
- Registrar hooks em `Plugin::define_*_hooks()`

### 3. Implementar Frontend
- Atualizar template em `templates/`
- Adicionar JavaScript em `assets/js/`
- Estilizar com CSS em `assets/css/`
- Garantir responsividade

### 4. Integrar
- Testar fluxo completo
- Verificar compatibilidade
- Atualizar documentação
- Incrementar versão

## Exemplos de Features

### Adicionar Novo Campo

Seguir guia em `novos-campos.md`:

1. **Meta_Extractor**:
```php
'novo_campo' => 'Nome da Meta Key',
```

2. **Template**:
```php
<th><?php esc_html_e('Novo Campo', 'inscricoes-pagas'); ?></th>
<td><?php echo esc_html($meta['novo_campo']); ?></td>
```

3. **CSV Exporter**:
```php
'Novo Campo', // header
$meta['novo_campo'], // valor
```

### Adicionar Filtro na Tabela

1. **Template - Adicionar select**:
```php
<select id="filtro-categoria">
    <option value="">Todas</option>
    <option value="sub12">Sub-12</option>
</select>
```

2. **JavaScript - Implementar filtro**:
```javascript
$('#filtro-categoria').on('change', function() {
    var categoria = $(this).val();
    $('tbody tr').each(function() {
        var rowCategoria = $(this).data('categoria');
        $(this).toggle(!categoria || rowCategoria === categoria);
    });
});
```

### Adicionar Operação AJAX

1. **Criar Handler**:
```php
class Ajax_Nova_Operacao extends Ajax_Base
{
    protected string $action = 'ip_nova_operacao';
    
    public function handle(): void
    {
        $this->verify_request();
        $data = sanitize_text_field($_POST['data'] ?? '');
        
        // Processar
        
        wp_send_json_success();
    }
}
```

2. **Registrar em Plugin**:
```php
$ajax_nova = new Ajax\Ajax_Nova_Operacao();
$this->loader->add_action('wp_ajax_ip_nova_operacao', $ajax_nova, 'handle');
```

3. **JavaScript**:
```javascript
$.ajax({
    url: ipData.ajaxUrl,
    type: 'POST',
    data: {
        action: 'ip_nova_operacao',
        nonce: ipData.nonce,
        data: valor
    },
    success: function(response) {
        console.log('Sucesso');
    }
});
```

## Melhores Práticas

### Código
- Seguir padrões existentes do projeto
- Usar tipagem estrita PHP 7.4+
- Documentar com PHPDoc
- Princípios SOLID e DRY
- Nomenclatura consistente

### Segurança
- Validar nonces em AJAX
- Sanitizar entrada
- Escapar saída
- Usar WooCommerce API

### Integração
- Não quebrar funcionalidades existentes
- Manter compatibilidade WooCommerce
- Testar com dados reais
- Verificar performance

## Checklist de Feature

- [ ] Requisitos compreendidos
- [ ] Backend implementado
- [ ] Frontend implementado
- [ ] AJAX funcionando (se aplicável)
- [ ] Segurança validada
- [ ] Testes manuais completos
- [ ] Documentação atualizada
- [ ] Versão incrementada
- [ ] Code review solicitado

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Workflow**: `.context/docs/development-workflow.md`
- **Adicionar Campos**: `novos-campos.md`
- **Segurança**: `.context/docs/security.md`
