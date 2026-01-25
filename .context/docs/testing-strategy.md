---
type: doc
name: testing-strategy
description: Test frameworks, patterns, coverage requirements, and quality gates
category: testing
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Estratégia de Testes

## Visão Geral

Atualmente o plugin **Inscrições Pagas** não possui testes automatizados implementados. Esta seção documenta a estratégia recomendada para implementação futura de testes.

## Frameworks Recomendados

### **PHPUnit**
- Framework padrão para testes PHP
- Integração com WordPress via `WP_UnitTestCase`
- Testes unitários para classes helpers

### **WordPress Test Suite**
- Ambiente de testes WordPress oficial
- Permite testar hooks, shortcodes e AJAX
- Requer configuração de banco de dados de teste

## Padrões de Teste

### **Testes Unitários**
Testar classes isoladamente:
- `Meta_Extractor` - Extração de metadados
- `CSV_Exporter` - Geração de CSV
- Helpers e utilitários

### **Testes de Integração**
Testar integração com WooCommerce:
- Criação de pedidos de teste
- Extração de metadados de pedidos reais
- Operações AJAX completas

### **Testes Manuais Atuais**
1. Criar produto WooCommerce com campos personalizados
2. Fazer pedido de teste
3. Marcar pedido como "Concluído"
4. Adicionar shortcode em página
5. Verificar renderização da tabela
6. Testar operações AJAX (checkboxes, observações)
7. Testar exportação CSV

## Requisitos de Cobertura

### **Meta Futura**
- Cobertura mínima: 70%
- Classes críticas: 90% (Meta_Extractor, Ajax handlers)
- Helpers: 80%

### **Áreas Críticas para Testar**
- Sanitização e validação de entrada
- Extração de metadados com múltiplas variações
- Handlers AJAX com validação de nonce
- Geração de CSV com caracteres especiais

## Quality Gates

### **Pré-commit**
- Verificação de sintaxe PHP (`php -l`)
- WordPress Coding Standards (futuro)

### **Pré-merge**
- Testes unitários passando (quando implementados)
- Code review aprovado
- Sem erros de lint críticos

### **Pré-release**
- Testes manuais completos
- Verificação de compatibilidade WooCommerce
- Teste em ambiente staging

## Estrutura de Testes Recomendada

```
tests/
├── bootstrap.php
├── unit/
│   ├── test-meta-extractor.php
│   ├── test-csv-exporter.php
│   └── test-ajax-handlers.php
├── integration/
│   ├── test-shortcode.php
│   └── test-woocommerce-integration.php
└── fixtures/
    └── sample-orders.php
```

## Exemplo de Teste

```php
class Test_Meta_Extractor extends WP_UnitTestCase {
    public function test_extract_nome_completo() {
        $item = $this->create_mock_order_item([
            'Nome completo' => 'João Silva'
        ]);
        
        $extractor = new Meta_Extractor();
        $data = $extractor->extract_from_item($item);
        
        $this->assertEquals('João Silva', $data['nome_completo']);
    }
}
```

## Recursos Relacionados

- **Desenvolvimento**: `development-workflow.md`
- **Arquitetura**: `architecture.md`
