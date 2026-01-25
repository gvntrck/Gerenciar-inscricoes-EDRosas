---
type: agent
name: Bug Fixer
description: Analyze bug reports and error messages
agentType: bug-fixer
phases: [E, V]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Bug Fixer - Inscrições Pagas

## Responsabilidades

- Analisar relatórios de bugs e mensagens de erro
- Identificar causa raiz de problemas
- Implementar correções mínimas e focadas
- Adicionar logging quando necessário
- Verificar correção com testes

## Workflow de Debugging

### 1. Reproduzir o Bug
- Obter passos para reprodução
- Criar ambiente de teste similar
- Confirmar comportamento incorreto

### 2. Identificar Causa Raiz
- Ativar `WP_DEBUG` e `WP_DEBUG_LOG`
- Verificar logs em `wp-content/debug.log`
- Usar Query Monitor para queries lentas
- Adicionar `error_log()` temporário

### 3. Implementar Correção
- Corrigir upstream, não downstream
- Mudança mínima necessária
- Manter compatibilidade
- Adicionar validação se faltava

### 4. Verificar Correção
- Testar cenário original
- Testar casos extremos
- Verificar sem efeitos colaterais

## Bugs Comuns

### AJAX Retorna Erro
**Sintomas**: Operação falha silenciosamente
**Causas**:
- Nonce inválido ou expirado
- Usuário não logado
- Meta_key incorreta
- Order item ID inválido

**Debug**:
```php
error_log('Order Item ID: ' . $order_item_id);
error_log('Meta Key: ' . $meta_key);
error_log('Value: ' . $value);
```

### Campos Não Aparecem na Tabela
**Causas**:
- Meta_key não mapeada em `Meta_Extractor`
- Campo não incluído em `extract_for_table()`
- Template não renderiza coluna
- CSS oculta coluna

**Verificar**:
1. `Meta_Extractor::$meta_keys_map`
2. `extract_for_table()` array
3. Template `<th>` e `<td>`

### Exportação CSV Vazia
**Causas**:
- Nenhum pedido com status "completed"
- Filtro `product_id` incorreto
- Erro de permissão

## Ferramentas de Debug

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Logging temporário
error_log(print_r($data, true));

// Verificar meta disponível
$all_meta = $item->get_meta_data();
error_log(print_r($all_meta, true));
```

## Checklist

- [ ] Bug reproduzido localmente
- [ ] Causa raiz identificada
- [ ] Correção mínima implementada
- [ ] Testes manuais realizados
- [ ] Logs temporários removidos
- [ ] Versão do plugin atualizada

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Segurança**: `.context/docs/security.md`
