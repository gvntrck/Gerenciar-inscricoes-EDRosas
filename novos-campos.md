# 📋 Manual: Como Adicionar Novos Campos ao Plugin Inscrições Pagas

Este manual descreve o passo a passo para adicionar novos campos de metadados do WooCommerce ao sistema de inscrições.

---

## 📂 Arquivos Envolvidos

| Arquivo | Descrição |
|---------|-----------|
| `includes/Helpers/class-meta-extractor.php` | Mapeamento de chaves de metadados |
| `includes/Helpers/class-csv-exporter.php` | Headers e dados do CSV de exportação |
| `templates/shortcode/main.php` | Template HTML da tabela |
| `assets/js/inscricoes-pagas.js` | JavaScript (se precisar de interatividade) |

---

## 🔧 Passo a Passo

### **Passo 1: Identificar a Meta Key do WooCommerce**

Primeiro, você precisa saber qual é a **meta_key** exata do campo no WooCommerce. 

#### Como descobrir a meta_key:
1. Acesse o banco de dados via phpMyAdmin
2. Procure na tabela `wp_woocommerce_order_itemmeta`
3. Ou use este código temporário para debug:

```php
// Adicione temporariamente em um pedido para ver todas as metas
add_action('woocommerce_admin_order_item_headers', function() {
    global $wpdb;
    $order_id = isset($_GET['post']) ? $_GET['post'] : 0;
    if ($order_id) {
        $order = wc_get_order($order_id);
        foreach ($order->get_items() as $item) {
            $all_meta = $item->get_meta_data();
            echo '<pre>';
            print_r($all_meta);
            echo '</pre>';
        }
    }
});
```

---

### **Passo 2: Adicionar ao Meta Extractor**

Edite o arquivo `includes/Helpers/class-meta-extractor.php`:

#### 2.1 Adicionar no array `$meta_keys_map` (linha ~32):

```php
private array $meta_keys_map = [
    // ... campos existentes ...
    
    // ➕ NOVO CAMPO: Adicione aqui
    'meu_novo_campo' => 'Nome da Meta Key no WooCommerce',
    
    // Se o campo tiver múltiplas variações de nome, use array:
    'meu_campo_variavel' => ['Nome Opção 1', 'Nome Opção 2'],
];
```

#### 2.2 Se for campo de detalhes, adicionar em `$detail_meta_keys` (linha ~76):

```php
private array $detail_meta_keys = [
    // ... campos existentes ...
    
    // ➕ NOVO CAMPO: Adicione aqui
    'Nome da Meta Key no WooCommerce',
];
```

#### 2.3 Se precisar de tratamento especial, adicionar em `extract_from_item()` (linha ~114):

```php
public function extract_from_item(\WC_Order_Item_Product $item): array
{
    $data = [];

    foreach ($this->meta_keys_map as $key => $meta_key) {
        $value = $this->get_meta_value($item, $meta_key);

        // ➕ Tratamento especial para seu novo campo
        if ($key === 'meu_novo_campo') {
            // Exemplo: formatar como número
            $value = $value ? intval($value) : 0;
            
            // Exemplo: formatar como data
            // $value = $value ? date('d/m/Y', strtotime($value)) : '';
            
            // Exemplo: valor padrão se vazio
            // $value = $value ?: 'N/A';
        }

        $data[$key] = $value;
    }

    return $data;
}
```

#### 2.4 Adicionar à lista de campos da tabela em `extract_for_table()` (linha ~149):

```php
public function extract_for_table(\WC_Order_Item_Product $item): array
{
    $table_keys = [
        // ... campos existentes ...
        
        // ➕ NOVO CAMPO: Adicione aqui
        'meu_novo_campo',
    ];

    // ...
}
```

---

### **Passo 3: Adicionar à Tabela HTML**

Edite o arquivo `templates/shortcode/main.php`:

#### 3.1 Adicionar o header da coluna (dentro do `<thead>`, após linha ~100):

```php
<thead>
    <tr>
        <!-- ... colunas existentes ... -->
        
        <!-- ➕ NOVO CAMPO: Adicione aqui -->
        <th class="ip-resizable" data-column="meu_novo_campo">
            <?php esc_html_e('Título do Campo', 'inscricoes-pagas'); ?>
        </th>
    </tr>
</thead>
```

#### 3.2 Adicionar a célula de dados (dentro do loop `<tbody>`, após linha ~139):

**Para campo de texto simples:**
```php
<td data-column="meu_novo_campo">
    <?php echo esc_html($meta['meu_novo_campo']); ?>
</td>
```

**Para campo checkbox editável:**
```php
<td data-column="meu_novo_campo">
    <input type="checkbox" 
           class="ip-generic-checkbox" 
           data-type="meu_novo_campo" 
           <?php checked($meta['meu_novo_campo'], '1'); ?>>
</td>
```

**Para campo com botão (como observações):**
```php
<td data-column="meu_novo_campo">
    <button class="ip-meu-campo-button" 
            data-order-item-id="<?php echo esc_attr($order_item_id); ?>" 
            data-valor="<?php echo esc_attr($meta['meu_novo_campo']); ?>">
        <?php esc_html_e('Ver', 'inscricoes-pagas'); ?>
    </button>
</td>
```

---

### **Passo 4: Adicionar à Exportação CSV**

Edite o arquivo `includes/Helpers/class-csv-exporter.php`:

#### 4.1 Adicionar header no array `$headers` (linha ~31):

```php
private array $headers = [
    // ... headers existentes ...
    
    // ➕ NOVO CAMPO: Adicione aqui
    'Título do Novo Campo',
];
```

#### 4.2 Adicionar o valor na linha do CSV em `write_order_rows()` (linha ~164):

```php
private function write_order_rows($output, \WC_Order $order): void
{
    // ...
    
    foreach ($order->get_items() as $item) {
        $meta = $this->meta_extractor->extract_from_item($item);

        $row = [
            // ... valores existentes ...
            
            // ➕ NOVO CAMPO: Adicione aqui
            $meta['meu_novo_campo'],
            
            // Para boolean/checkbox:
            // $meta['meu_novo_campo'] == '1' ? 'Sim' : 'Não',
        ];

        fputcsv($output, $row);
    }
}
```

---

### **Passo 5: Adicionar ao Modal de Detalhes (Opcional)**

Se o campo deve aparecer no modal de detalhes, edite `class-meta-extractor.php`:

#### 5.1 Adicionar em `prepare_detail_data()` (linha ~218):

```php
public function prepare_detail_data(array $raw_metas): array
{
    $data = [
        // ... campos existentes ...
        
        // ➕ NOVO CAMPO: Adicione aqui
        'Nome da Meta Key no WooCommerce' => '',
    ];

    // ...
}
```

---

### **Passo 6: Adicionar Handler AJAX (Se for Editável)**

Se o campo precisa ser editável via AJAX, você tem duas opções:

#### Opção A: Usar o handler genérico existente (para checkboxes)

O campo será salvo automaticamente se usar a classe `ip-generic-checkbox` com `data-type="sua_meta_key"`.

#### Opção B: Criar um novo handler AJAX

1. Criar arquivo `includes/Ajax/class-ajax-meu-campo.php`:

```php
<?php
namespace InscricoesPagas\Ajax;

if (!defined('ABSPATH')) {
    exit;
}

class Ajax_Meu_Campo extends Ajax_Base
{
    protected string $action = 'ip_update_meu_campo';

    public function handle(): void
    {
        // Verificar segurança
        $this->verify_request();

        $order_item_id = absint($_POST['order_item_id'] ?? 0);
        $valor = sanitize_text_field($_POST['valor'] ?? '');

        if (!$order_item_id) {
            wp_send_json_error(['message' => 'ID inválido']);
        }

        // Atualizar meta
        wc_update_order_item_meta($order_item_id, 'minha_meta_key', $valor);

        wp_send_json_success(['message' => 'Atualizado com sucesso']);
    }
}
```

2. Registrar em `includes/class-plugin.php` no método `define_ajax_hooks()`:

```php
private function define_ajax_hooks(): void
{
    // ... handlers existentes ...
    
    // ➕ NOVO HANDLER
    $ajax_meu_campo = new Ajax\Ajax_Meu_Campo();
    $this->loader->add_action('wp_ajax_ip_update_meu_campo', $ajax_meu_campo, 'handle');
}
```

3. Adicionar JavaScript em `assets/js/inscricoes-pagas.js`:

```javascript
// Handler para o novo campo
$(document).on('change', '.ip-meu-campo-input', function() {
    var $this = $(this);
    var orderItemId = $this.closest('tr').data('order-item-id');
    var valor = $this.val();
    
    $.ajax({
        url: ipData.ajaxUrl,
        type: 'POST',
        data: {
            action: 'ip_update_meu_campo',
            nonce: ipData.nonce,
            order_item_id: orderItemId,
            valor: valor
        },
        success: function(response) {
            if (response.success) {
                console.log('Campo atualizado!');
            }
        }
    });
});
```

---

## 📋 Checklist Rápido

Ao adicionar um novo campo, verifique:

- [ ] Meta key identificada corretamente
- [ ] Adicionado em `$meta_keys_map` no Meta Extractor
- [ ] Adicionado em `extract_for_table()` (se aparecer na tabela)
- [ ] Adicionado em `$detail_meta_keys` (se aparecer nos detalhes)
- [ ] Header adicionado em `<thead>` do template
- [ ] Célula `<td>` adicionada no loop do template
- [ ] Header adicionado no CSV Exporter
- [ ] Valor adicionado na linha do CSV
- [ ] Handler AJAX criado (se editável)
- [ ] JavaScript adicionado (se editável)
- [ ] Versão do plugin atualizada

---

## 🔢 Atualizar Versão do Plugin

Após adicionar novos campos, atualize a versão em dois lugares:

1. **Header do plugin** (`inscricoes-pagas.php`, linha 5):
```php
* Version:           2.0.4
```

2. **Constante** (`inscricoes-pagas.php`, linha 25):
```php
define('INSCRICOES_PAGAS_VERSION', '2.0.4');
```

---

## 📝 Exemplo Completo: Adicionando Campo "CPF"

### 1. Meta Extractor (`class-meta-extractor.php`):

```php
// Em $meta_keys_map:
'cpf' => 'CPF do Participante',

// Em extract_for_table() - $table_keys:
'cpf',
```

### 2. Template (`main.php`):

```php
// No <thead>:
<th class="ip-resizable" data-column="cpf">
    <?php esc_html_e('CPF', 'inscricoes-pagas'); ?>
</th>

// No <tbody>:
<td data-column="cpf"><?php echo esc_html($meta['cpf']); ?></td>
```

### 3. CSV Exporter (`class-csv-exporter.php`):

```php
// Em $headers:
'CPF',

// Em write_order_rows():
$meta['cpf'],
```

---

## ❓ FAQ

**P: O campo não aparece mesmo após adicionar?**
R: Verifique se a meta_key está escrita exatamente como no banco de dados (case-sensitive).

**P: Como adicionar campo que só aparece às vezes?**
R: Use o formato de array no mapeamento para tentar múltiplas variações.

**P: Preciso limpar cache?**
R: Sim, limpe o cache do navegador e do WordPress se estiver usando plugins de cache.

**P: Como testar se está funcionando?**
R: Use o modal de "Detalhes" para verificar se o valor está sendo extraído corretamente.

---

*Atualizado em: Janeiro 2026*
*Versão do Plugin: 2.0.3*
