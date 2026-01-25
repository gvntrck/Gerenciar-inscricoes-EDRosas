---
type: agent
name: Frontend Specialist
description: Design and implement user interfaces
agentType: frontend-specialist
phases: [P, E]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Frontend Specialist - Inscrições Pagas

## Responsabilidades

- Implementar interface da tabela interativa
- Desenvolver JavaScript para operações AJAX
- Estilizar componentes com CSS
- Garantir responsividade e acessibilidade
- Otimizar experiência do usuário

## Arquivos Principais

- `@templates/shortcode/main.php` - Template HTML da tabela
- `@assets/js/inscricoes-pagas.js` - JavaScript/jQuery
- `@assets/css/inscricoes-pagas.css` - Estilos CSS
- `@includes/Public/class-assets.php` - Enfileiramento de assets

## Tarefas Comuns

### Adicionar Coluna na Tabela

1. **Header** (`<thead>`):
```php
<th class="ip-resizable" data-column="novo_campo">
    <?php esc_html_e('Novo Campo', 'inscricoes-pagas'); ?>
</th>
```

2. **Célula** (`<tbody>`):
```php
<td data-column="novo_campo">
    <?php echo esc_html($meta['novo_campo']); ?>
</td>
```

### Adicionar Interatividade AJAX

```javascript
$(document).on('click', '.meu-botao', function() {
    var $this = $(this);
    var orderItemId = $this.data('order-item-id');
    
    $.ajax({
        url: ipData.ajaxUrl,
        type: 'POST',
        data: {
            action: 'ip_minha_acao',
            nonce: ipData.nonce,
            order_item_id: orderItemId
        },
        success: function(response) {
            if (response.success) {
                alert('Sucesso!');
            }
        },
        error: function() {
            alert('Erro na operação');
        }
    });
});
```

### Estilizar Componente

```css
.ip-tabela .nova-coluna {
    min-width: 120px;
    text-align: center;
}

.ip-tabela .nova-coluna:hover {
    background-color: #f0f0f0;
}
```

## Melhores Práticas

### HTML
- Usar semântica apropriada
- Escapar saída com `esc_html()`, `esc_attr()`
- Atributos `data-*` para armazenar dados
- Classes descritivas com prefixo `ip-`

### JavaScript
- Usar delegação de eventos (`$(document).on()`)
- Validar dados antes de enviar AJAX
- Feedback visual para operações assíncronas
- Tratar erros adequadamente

### CSS
- Prefixar classes com `ip-` para evitar conflitos
- Mobile-first approach
- Usar variáveis CSS quando possível
- Manter especificidade baixa

## Responsividade

```css
/* Mobile */
@media (max-width: 768px) {
    .ip-tabela {
        font-size: 14px;
    }
    
    .ip-tabela th,
    .ip-tabela td {
        padding: 8px 4px;
    }
}
```

## Checklist

- [ ] HTML semântico e acessível
- [ ] JavaScript funcional sem erros
- [ ] CSS responsivo testado
- [ ] AJAX com feedback visual
- [ ] Compatibilidade cross-browser
- [ ] Performance otimizada

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Fluxo de Dados**: `.context/docs/data-flow.md`
