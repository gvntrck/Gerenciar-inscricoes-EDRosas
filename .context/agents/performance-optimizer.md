---
type: agent
name: Performance Optimizer
description: Identify performance bottlenecks
agentType: performance-optimizer
phases: [E, V]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Performance Optimizer - Inscrições Pagas

## Responsabilidades

- Identificar gargalos de performance
- Otimizar queries do banco de dados
- Reduzir tempo de carregamento
- Melhorar experiência do usuário

## Áreas de Otimização

### Queries do Banco

**Problema**: Buscar todos os pedidos sem limite
```php
$orders = wc_get_orders(['status' => 'completed', 'limit' => -1]);
```

**Solução**: Implementar paginação
```php
$orders = wc_get_orders([
    'status' => 'completed',
    'limit' => 50,
    'page' => $page
]);
```

### Cache

**Implementar cache transiente**:
```php
$cache_key = 'ip_orders_' . $product_id;
$orders = get_transient($cache_key);

if (false === $orders) {
    $orders = wc_get_orders([...]);
    set_transient($cache_key, $orders, HOUR_IN_SECONDS);
}
```

### Assets

**Minificar CSS/JS**:
- Usar versão minificada em produção
- Combinar arquivos quando possível
- Lazy load para recursos não críticos

### AJAX

**Debounce para operações frequentes**:
```javascript
var debounceTimer;
$('.search-input').on('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() {
        // Executar busca
    }, 300);
});
```

## Ferramentas de Análise

### Query Monitor
- Queries lentas
- Queries duplicadas
- Tempo de execução

### Browser DevTools
- Network waterfall
- JavaScript performance
- Memory leaks

### GTmetrix / PageSpeed Insights
- Tempo de carregamento
- Métricas Core Web Vitals
- Recomendações de otimização

## Métricas Alvo

- **Tempo de carregamento**: < 2s
- **Time to Interactive**: < 3s
- **Queries por página**: < 50
- **Tamanho da página**: < 1MB

## Checklist

- [ ] Queries otimizadas
- [ ] Cache implementado
- [ ] Assets minificados
- [ ] Lazy loading usado
- [ ] Sem N+1 queries
- [ ] Performance testada

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Database**: `.context/agents/database-specialist.md`
