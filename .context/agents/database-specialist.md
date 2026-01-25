---
type: agent
name: Database Specialist
description: Design and optimize database schemas
agentType: database-specialist
phases: [P, E]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Database Specialist - Inscrições Pagas

## Responsabilidades

- Otimizar queries do banco de dados
- Garantir integridade de dados
- Monitorar performance de queries
- Recomendar índices quando necessário

## Estrutura de Dados

### Tabelas Utilizadas
- `wp_posts` - Pedidos WooCommerce
- `wp_postmeta` - Metadados de pedidos
- `wp_woocommerce_order_items` - Itens de pedido
- `wp_woocommerce_order_itemmeta` - Metadados de itens

### HPOS (High-Performance Order Storage)
O plugin declara compatibilidade com HPOS, que usa tabelas customizadas:
- `wp_wc_orders`
- `wp_wc_orders_meta`
- `wp_wc_order_operational_data`

## Queries Principais

### Buscar Pedidos Completos
```php
$orders = wc_get_orders([
    'status' => 'completed',
    'limit' => -1
]);
```

### Atualizar Metadado
```php
wc_update_order_item_meta($item_id, $meta_key, $value);
```

## Otimizações

### Evitar N+1 Queries
- Usar `wc_get_orders()` com eager loading
- Cachear resultados quando apropriado

### Índices Importantes
- `post_status` em `wp_posts`
- `meta_key` em `wp_postmeta`
- `order_item_id` em `wp_woocommerce_order_itemmeta`

## Monitoramento

**Query Monitor Plugin**:
- Identificar queries lentas
- Verificar queries duplicadas
- Analisar uso de índices

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Fluxo de Dados**: `.context/docs/data-flow.md`
