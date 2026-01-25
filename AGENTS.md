# AGENTS.md

## Dev environment tips
- Configure ambiente WordPress local (Local by Flywheel, XAMPP ou Docker)
- Instalar WordPress 5.0+ e WooCommerce 4.0+
- Ativar `WP_DEBUG` e `WP_DEBUG_LOG` durante desenvolvimento
- Usar Query Monitor plugin para análise de performance
- Testar com pedidos WooCommerce reais em status "Concluído"

## Testing instructions
- Atualmente não há testes automatizados implementados
- Testes manuais obrigatórios:
  1. Criar produto WooCommerce com campos personalizados
  2. Fazer pedido de teste e marcar como "Concluído"
  3. Adicionar shortcode `[inscricoes_pagas]` em página
  4. Testar operações AJAX (checkboxes, observações, detalhes)
  5. Testar exportação CSV
  6. Verificar responsividade mobile
- Para testes futuros: Configurar PHPUnit com WordPress Test Suite

## PR instructions
- Seguir Semantic Versioning (MAJOR.MINOR.PATCH)
- **SEMPRE** atualizar número de versão no header do plugin (`inscricoes-pagas.php`)
- Seguir WordPress Coding Standards (erros de lint toleráveis)
- Aplicar princípios SOLID, Clean Code, DRY, KISS
- Documentar mudanças significativas
- Testar manualmente antes de commit
- Commits descritivos em português brasileiro

## Repository map

### `assets/`
**Conteúdo**: Arquivos CSS e JavaScript do frontend
- `css/inscricoes-pagas.css` - Estilos da tabela interativa
- `js/inscricoes-pagas.js` - JavaScript/jQuery para AJAX e interações

**Quando editar**:
- Adicionar estilos para novos componentes
- Implementar novas interações AJAX
- Melhorar responsividade mobile
- Otimizar performance frontend

### `includes/`
**Conteúdo**: Classes PHP principais do plugin (arquitetura modular)
- `class-plugin.php` - Orquestrador principal
- `class-loader.php` - Gerenciador de hooks WordPress
- `Admin/` - Funcionalidades do painel administrativo
- `Public/` - Frontend (shortcode, assets)
- `Ajax/` - Handlers AJAX específicos por operação
- `Helpers/` - Lógica de negócio reutilizável (Meta_Extractor, CSV_Exporter)

**Quando editar**:
- Adicionar novos handlers AJAX
- Implementar novas funcionalidades
- Modificar extração de metadados
- Adicionar helpers reutilizáveis
- Estender funcionalidades administrativas

### `inscricoes-pagas.php`
**Conteúdo**: Arquivo principal do plugin WordPress
- Header do plugin com metadados
- Definição de constantes (versão, paths)
- Autoloader customizado
- Verificação de requisitos (PHP, WordPress, WooCommerce)
- Hooks de ativação/desativação
- Inicialização do plugin

**Quando editar**:
- **SEMPRE** ao fazer qualquer mudança no plugin (atualizar versão)
- Adicionar novas constantes globais
- Modificar requisitos mínimos
- Alterar lógica de ativação/desativação

### `insricoespagas.zip`
**Conteúdo**: Arquivo ZIP do plugin (distribuição)

**Quando editar**:
- Recriar após mudanças significativas para distribuição
- Não versionar no Git (adicionar ao `.gitignore`)

### `novos-campos.md`
**Conteúdo**: Guia completo (413 linhas) para adicionar novos campos de metadados WooCommerce
- Passo a passo detalhado
- Arquivos envolvidos
- Exemplos de código
- Checklist de verificação

**Quando editar**:
- Atualizar se processo de adicionar campos mudar
- Adicionar novos exemplos
- Corrigir instruções desatualizadas
- Documentar novos casos de uso

### `readme.md`
**Conteúdo**: Documentação básica de uso do plugin
- Como usar o shortcode `[inscricoes_pagas]`
- Parâmetro `product_id` para filtrar

**Quando editar**:
- Adicionar novos recursos ao plugin
- Documentar novos parâmetros do shortcode
- Atualizar instruções de instalação
- Adicionar FAQ

### `templates/`
**Conteúdo**: Templates PHP para renderização HTML
- `shortcode/main.php` - Template da tabela interativa de inscrições

**Quando editar**:
- Adicionar novas colunas na tabela
- Modificar estrutura HTML
- Adicionar novos elementos de UI
- Melhorar acessibilidade

### `uninstall.php`
**Conteúdo**: Script executado ao desinstalar plugin
- Limpeza de dados (se necessário)
- Remoção de opções WordPress

**Quando editar**:
- Adicionar limpeza de novos dados persistidos
- Remover opções WordPress criadas pelo plugin
- Implementar lógica de desinstalação segura

### `.context/`
**Conteúdo**: Documentação técnica e playbooks de agentes
- `docs/` - Arquitetura, fluxo de dados, segurança, testes, etc.
- `agents/` - Playbooks específicos por especialidade

**Quando editar**:
- Atualizar após mudanças arquiteturais significativas
- Documentar novas decisões de design
- Manter sincronizado com código

## AI Context References
- **Documentação técnica**: `.context/docs/README.md`
- **Playbooks de agentes**: `.context/agents/README.md`
- **Guia de campos**: `novos-campos.md`
- **Visão geral**: `.context/docs/project-overview.md`
- **Arquitetura**: `.context/docs/architecture.md`
- **Fluxo de dados**: `.context/docs/data-flow.md`
- **Segurança**: `.context/docs/security.md`
- **Desenvolvimento**: `.context/docs/development-workflow.md`

## Quick Start para Agentes

1. **Entender o projeto**: Ler `.context/docs/project-overview.md`
2. **Adicionar campo**: Seguir `novos-campos.md`
3. **Implementar feature**: Consultar `.context/agents/feature-developer.md`
4. **Corrigir bug**: Consultar `.context/agents/bug-fixer.md`
5. **Revisar código**: Usar checklist em `.context/agents/code-reviewer.md`
6. **Atualizar versão**: Editar header em `inscricoes-pagas.php`
