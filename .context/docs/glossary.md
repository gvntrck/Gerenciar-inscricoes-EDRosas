---
type: doc
name: glossary
description: Project terminology, type definitions, domain entities, and business rules
category: glossary
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Glossary & Domain Concepts

Terminologia e conceitos de domínio específicos do plugin Inscrições Pagas.

## Type Definitions

### Namespace Principal
- **`InscricoesPagas\`** — Namespace raiz para todas as classes do plugin

### Classes Principais
- [`InscricoesPagas\Plugin`](../../includes/class-plugin.php) — Classe orquestradora principal
- [`InscricoesPagas\Loader`](../../includes/class-loader.php) — Registrador de hooks
- [`InscricoesPagas\Activator`](../../includes/class-activator.php) — Lógica de ativação
- [`InscricoesPagas\Deactivator`](../../includes/class-deactivator.php) — Lógica de desativação

## Enumerations

### Status de Inscrição
- **`pending`** — Aguardando pagamento
- **`paid`** — Pagamento confirmado
- **`cancelled`** — Inscrição cancelada
- **`refunded`** — Reembolsado

### Status de Pagamento WooCommerce
- **`on-hold`** — Aguardando confirmação
- **`processing`** — Em processamento
- **`completed`** — Concluído
- **`refunded`** — Reembolsado

## Core Terms

| Termo | Definição | Onde Aparece |
|-------|-----------|--------------|
| **Inscrição** | Registro de um participante em um evento de xadrez | Tabelas do banco, handlers AJAX |
| **Evento** | Competição ou torneio de xadrez | Produtos WooCommerce |
| **Participante** | Pessoa inscrita em um evento | Dados de inscrição |
| **Rating** | Classificação de habilidade do jogador | Campos de inscrição |
| **Federação** | Entidade de xadrez do jogador (ex: CBX) | Campos de inscrição |

## Acronyms & Abbreviations

| Acrônimo | Expansão | Contexto |
|----------|----------|----------|
| **WC** | WooCommerce | Integração de pagamentos |
| **HPOS** | High-Performance Order Storage | Compatibilidade WooCommerce |
| **CBX** | Confederação Brasileira de Xadrez | Federação de xadrez |
| **FIDE** | Fédération Internationale des Échecs | Federação internacional |
| **CSV** | Comma-Separated Values | Formato de exportação |
| **AJAX** | Asynchronous JavaScript and XML | Requisições assíncronas |

## Personas / Actors

### Organizador de Eventos
- **Meta**: Gerenciar inscrições de torneios de xadrez
- **Fluxos principais**: Visualizar inscrições, exportar listas, editar dados
- **Dores**: Controle manual de pagamentos, listas desatualizadas

### Participante/Jogador
- **Meta**: Inscrever-se em eventos de xadrez
- **Fluxos principais**: Realizar inscrição, efetuar pagamento
- **Dores**: Processo de inscrição complexo, falta de confirmação

### Administrador WordPress
- **Meta**: Manter o plugin funcionando corretamente
- **Fluxos principais**: Configuração, atualizações, troubleshooting
- **Dores**: Compatibilidade com outros plugins

## Domain Rules & Invariants

### Regras de Negócio
1. Uma inscrição só é válida após confirmação de pagamento
2. Cada participante pode ter múltiplas inscrições em diferentes eventos
3. Exportação CSV respeita filtros aplicados na tabela
4. Edição de dados requer permissão de administrador

### Validações
- CPF deve ser válido (quando aplicável)
- Email deve ser único por inscrição
- Rating deve ser numérico e dentro do range FIDE

Consulte [Project Overview](./project-overview.md) para contexto geral.
