---
type: doc
name: glossary
description: Project terminology, type definitions, domain entities, and business rules
category: glossary
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Glossário e Conceitos do Domínio

## Termos Principais

### **Inscrição**
Registro de um participante em um evento de xadrez, representado por um item de pedido WooCommerce. Contém metadados como nome, email, titulação, IDs CBX/FIDE, etc.

### **Inscrito**
Status binário (checkbox) indicando se o participante está confirmado/inscrito no evento. Campo `inscrito_arbitro` nos metadados.

### **Meta_key**
Chave de metadado do WooCommerce que armazena informações personalizadas de produtos/pedidos. Exemplos: "Nome completo", "E-mail", "ID CBX".

### **Order Item**
Item individual dentro de um pedido WooCommerce. Representa um produto comprado (inscrição em evento). Acessado via `WC_Order_Item_Product`.

### **Shortcode**
Tag WordPress que renderiza conteúdo dinâmico. Este plugin usa `[inscricoes_pagas]` para exibir tabela de inscrições.

### **Nonce**
Token de segurança WordPress usado para validar requisições AJAX e prevenir CSRF. Verificado via `check_ajax_referer()`.

### **HPOS**
High-Performance Order Storage - novo sistema de armazenamento de pedidos do WooCommerce que usa tabelas customizadas em vez de posts.

### **Árbitro**
Organizador/administrador do evento que gerencia inscrições. Campos como `inscrito_arbitro`, `cbx_arbitro`, `obs_arbitro` são controlados pelo árbitro.

### **CBX**
Confederação Brasileira de Xadrez. ID CBX é o identificador oficial de jogadores no Brasil.

### **FIDE**
Fédération Internationale des Échecs (Federação Internacional de Xadrez). ID FIDE é o identificador internacional de jogadores.

### **Titulação**
Classificação oficial do jogador de xadrez (ex: Mestre Internacional, Grande Mestre, sem título).

## Definições de Tipos

### **Meta_Extractor::$meta_keys_map**
```php
array<string, string|array<string>>
```
Mapeamento de chaves internas para meta_keys do WooCommerce. Quando valor é array, tenta cada opção em ordem.

### **Meta_Extractor::extract_from_item()**
```php
extract_from_item(WC_Order_Item_Product $item): array
```
Retorna array associativo com todos os campos mapeados extraídos do item.

### **Ajax Handlers::handle()**
```php
handle(): void
```
Processa requisição AJAX, valida segurança, executa operação e retorna JSON.

## Enumerações

### **Status de Pedido WooCommerce**
- `completed` - Pedido concluído (inscrições exibidas na tabela)
- `processing` - Em processamento
- `pending` - Aguardando pagamento
- `cancelled` - Cancelado
- `refunded` - Reembolsado

### **Tipos de Campos**
- **Texto**: Nome, email, clube, cidade
- **Numérico**: ID CBX, ID FIDE, quantidade de canecas
- **Data**: Data de nascimento
- **Checkbox**: Inscrito, CBX Árbitro, Hotel, PCD
- **Textarea**: Observações

## Acrônimos e Abreviações

- **IP**: Inscrições Pagas (prefixo do plugin)
- **WC**: WooCommerce
- **WP**: WordPress
- **AJAX**: Asynchronous JavaScript and XML
- **CSV**: Comma-Separated Values
- **PCD**: Pessoa com Deficiência
- **DRY**: Don't Repeat Yourself
- **SOLID**: Princípios de design orientado a objetos
- **PSR-4**: PHP Standard Recommendation 4 (autoloading)

## Personas / Atores

### **Organizador de Evento (Árbitro)**
- **Objetivo**: Gerenciar inscrições de eventos de xadrez de forma eficiente
- **Workflows principais**:
  1. Visualizar lista de inscritos
  2. Filtrar por categoria/produto
  3. Marcar participantes como inscritos
  4. Adicionar observações sobre participantes
  5. Exportar lista para CSV
  6. Verificar dados de contato e IDs oficiais
- **Pain points resolvidos**:
  - Gerenciamento manual via planilhas
  - Dificuldade em filtrar e ordenar dados
  - Falta de integração com sistema de pagamentos

### **Participante (Jogador de Xadrez)**
- **Objetivo**: Inscrever-se em eventos de xadrez
- **Workflow**: Preenche formulário WooCommerce com dados pessoais e realiza pagamento
- **Dados fornecidos**: Nome, email, celular, titulação, clube, IDs CBX/FIDE, data de nascimento

## Regras de Domínio e Invariantes

### **Validação de Dados**
- **ID CBX/FIDE**: Devem ser numéricos, 0 se não possuir
- **Email**: Deve ser válido (validação WooCommerce)
- **Data de Nascimento**: Formato de data válido
- **Status Inscrito**: Booleano (0 ou 1)

### **Regras de Negócio**
1. **Apenas pedidos "completed" são exibidos** - Garante que só inscrições pagas apareçam
2. **Filtro por product_id é opcional** - Permite visualizar todos os eventos ou apenas um
3. **Campos de árbitro são editáveis** - `inscrito_arbitro`, `cbx_arbitro`, `obs_arbitro`, `hotel`
4. **Exportação CSV inclui todos os pedidos filtrados** - Não há limite de registros

### **Restrições de Segurança**
- Apenas usuários logados podem acessar operações AJAX
- Nonces devem ser válidos para todas as requisições
- Dados de entrada devem ser sanitizados antes de persistir
- Dados de saída devem ser escapados antes de renderizar

### **Considerações de Localização**
- Plugin preparado para i18n (text domain: `inscricoes-pagas`)
- Formato de data brasileiro (dd/mm/yyyy)
- Campos específicos do Brasil (ID CBX, DDD de celular)

## Recursos Relacionados

- **Visão Geral**: `project-overview.md`
- **Arquitetura**: `architecture.md`
