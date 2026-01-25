---
type: doc
name: development-workflow
description: Day-to-day engineering processes, branching, and contribution guidelines
category: workflow
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Workflow de Desenvolvimento

## Processo de Desenvolvimento

O desenvolvimento do plugin **Inscrições Pagas** segue práticas WordPress padrão com foco em manutenibilidade e extensibilidade. Mudanças devem seguir princípios SOLID, Clean Code e DRY, mantendo compatibilidade com WordPress 5.0+ e WooCommerce 4.0+.

## Branching e Releases

### **Modelo de Branching**
- **main/master**: Código de produção estável
- **develop**: Desenvolvimento ativo (se aplicável)
- **feature/***: Novas funcionalidades
- **fix/***: Correções de bugs

### **Versionamento**
- Segue **Semantic Versioning** (MAJOR.MINOR.PATCH)
- Versão atual: **2.0.4**
- Atualizar em dois locais ao fazer release:
  1. Header do plugin (`inscricoes-pagas.php` linha 5)
  2. Constante `INSCRICOES_PAGAS_VERSION` (linha 25)

### **Cadência de Releases**
- Releases conforme necessário (sem calendário fixo)
- Tags Git para cada versão: `v2.0.4`

## Desenvolvimento Local

### **Instalação**
```bash
# 1. Clone o repositório no diretório de plugins WordPress
cd wp-content/plugins/
git clone [repo-url] inscricoes-pagas

# 2. Certifique-se que WooCommerce está instalado e ativo

# 3. Ative o plugin via painel WordPress
# Plugins > Inscrições Pagas > Ativar
```

### **Execução Local**
```bash
# Não há build necessário - plugin PHP puro
# Apenas ative o plugin no WordPress

# Para testar:
# 1. Crie produtos WooCommerce com campos personalizados
# 2. Faça pedidos de teste com status "Concluído"
# 3. Adicione shortcode [inscricoes_pagas] em uma página
# 4. Acesse a página para ver a tabela
```

### **Estrutura de Desenvolvimento**
- **Editar PHP**: Arquivos em `includes/`, `inscricoes-pagas.php`
- **Editar CSS**: `assets/css/inscricoes-pagas.css`
- **Editar JS**: `assets/js/inscricoes-pagas.js`
- **Editar Templates**: `templates/shortcode/main.php`

### **Debugging**
```php
// Ativar modo debug no wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Logs ficam em wp-content/debug.log
```

## Expectativas de Code Review

### **Checklist de Review**
- [ ] Código segue WordPress Coding Standards
- [ ] Tipagem estrita PHP 7.4+ utilizada
- [ ] Sanitização e escape adequados (`sanitize_*`, `esc_*`)
- [ ] Nonces validados em operações AJAX
- [ ] Sem hard-coded strings (usar i18n: `__()`, `_e()`)
- [ ] Documentação PHPDoc atualizada
- [ ] Versão do plugin incrementada se necessário
- [ ] Compatibilidade com WooCommerce HPOS mantida
- [ ] Sem erros de lint toleráveis (conforme regra global)

### **Aprovações Necessárias**
- Pelo menos 1 aprovação para merge
- Autor do código não pode aprovar próprio PR

### **Padrões de Código**
- **Indentação**: 4 espaços (não tabs)
- **Nomenclatura**: `snake_case` para funções, `PascalCase` para classes
- **Namespaces**: `InscricoesPagas\[Módulo]\[Classe]`
- **Arquivos**: `class-nome-da-classe.php`

## Adicionando Novos Campos

Para adicionar campos de metadados WooCommerce à tabela, siga o guia completo em `novos-campos.md`. Resumo:

1. **Identificar meta_key** no WooCommerce
2. **Adicionar em Meta_Extractor**: `$meta_keys_map`, `extract_for_table()`
3. **Adicionar no Template**: Header `<th>` e célula `<td>`
4. **Adicionar no CSV Exporter**: Header e valor da linha
5. **Criar handler AJAX** (se editável)
6. **Atualizar versão do plugin**

## Tarefas de Onboarding

### **Novos Desenvolvedores**
1. Configure ambiente WordPress local (XAMPP, Local, Docker)
2. Instale WooCommerce e configure loja de teste
3. Clone o plugin e ative no WordPress
4. Leia `novos-campos.md` para entender extensibilidade
5. Explore classes em `includes/` para entender arquitetura
6. Faça pedido de teste e veja tabela renderizada
7. Teste operações AJAX (editar checkbox, observação, exportar CSV)

### **Primeiros Issues**
- Adicionar novo campo à tabela (seguir `novos-campos.md`)
- Melhorar estilização CSS da tabela
- Adicionar filtro por data de inscrição
- Implementar paginação na tabela

## Recursos Relacionados

- **Testes**: `testing-strategy.md`
- **Ferramentas**: `tooling.md`
- **Arquitetura**: `architecture.md`
