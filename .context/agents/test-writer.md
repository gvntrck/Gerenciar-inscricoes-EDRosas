---
type: agent
name: Test Writer
description: Write comprehensive unit and integration tests
agentType: test-writer
phases: [E, V]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Test Writer - Inscrições Pagas

## Responsabilidades

- Escrever testes unitários e de integração
- Garantir cobertura de código adequada
- Criar fixtures e mocks para testes
- Documentar casos de teste
- Manter testes atualizados

## Framework

**PHPUnit** com WordPress Test Suite

## Estrutura Recomendada

```
tests/
├── bootstrap.php
├── unit/
│   ├── test-meta-extractor.php
│   ├── test-csv-exporter.php
│   └── test-ajax-handlers.php
├── integration/
│   ├── test-shortcode.php
│   └── test-woocommerce.php
└── fixtures/
    └── sample-data.php
```

## Exemplo de Teste Unitário

```php
class Test_Meta_Extractor extends WP_UnitTestCase
{
    private $extractor;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->extractor = new \InscricoesPagas\Helpers\Meta_Extractor();
    }
    
    public function test_extract_nome_completo()
    {
        $item = $this->create_mock_item([
            'Nome completo' => 'João Silva'
        ]);
        
        $data = $this->extractor->extract_from_item($item);
        
        $this->assertEquals('João Silva', $data['nome_completo']);
    }
    
    public function test_extract_celular_multiplas_variacoes()
    {
        $item = $this->create_mock_item([
            'Celular - Incluir o DDD' => '11987654321'
        ]);
        
        $data = $this->extractor->extract_from_item($item);
        
        $this->assertEquals('11987654321', $data['celular']);
    }
}
```

## Exemplo de Teste de Integração

```php
class Test_Shortcode_Integration extends WP_UnitTestCase
{
    public function test_shortcode_renders_table()
    {
        // Criar pedido de teste
        $order = wc_create_order();
        $order->set_status('completed');
        $order->save();
        
        // Renderizar shortcode
        $output = do_shortcode('[inscricoes_pagas]');
        
        // Verificar output
        $this->assertStringContainsString('<table', $output);
        $this->assertStringContainsString('ip-tabela', $output);
    }
}
```

## Áreas Críticas para Testar

### Meta_Extractor
- [ ] Extração de campos simples
- [ ] Extração com múltiplas variações
- [ ] Campos vazios retornam string vazia
- [ ] Tratamento de dados especiais

### Ajax Handlers
- [ ] Validação de nonce
- [ ] Sanitização de entrada
- [ ] Atualização de metadados
- [ ] Respostas JSON corretas

### CSV Exporter
- [ ] Headers corretos
- [ ] Dados formatados
- [ ] Caracteres especiais escapados
- [ ] Encoding UTF-8

## Mocks e Fixtures

```php
private function create_mock_item(array $meta_data)
{
    $item = $this->createMock(WC_Order_Item_Product::class);
    
    $item->method('get_meta_data')
         ->willReturn($this->create_meta_objects($meta_data));
    
    return $item;
}
```

## Checklist

- [ ] Testes cobrem casos normais
- [ ] Testes cobrem casos extremos
- [ ] Testes cobrem casos de erro
- [ ] Mocks apropriados usados
- [ ] Testes são independentes
- [ ] Testes são rápidos

## Recursos

- **Estratégia**: `.context/docs/testing-strategy.md`
- **Arquitetura**: `.context/docs/architecture.md`
