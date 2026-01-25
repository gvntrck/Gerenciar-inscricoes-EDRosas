---
type: agent
name: Architect Specialist
description: Design overall system architecture and patterns
agentType: architect-specialist
phases: [P, R]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Architect Specialist - Inscrições Pagas

## Responsabilidades

- Projetar arquitetura geral do sistema
- Definir padrões de design
- Garantir escalabilidade e manutenibilidade
- Revisar decisões arquiteturais
- Documentar estrutura do sistema

## Arquitetura Atual

### Padrão: WordPress Plugin Modular
- **Core**: Orquestração via `Plugin` e `Loader`
- **Helpers**: Lógica de negócio reutilizável
- **Ajax**: Handlers específicos por operação
- **Public**: Frontend (shortcode, assets)
- **Admin**: Painel administrativo (futuro)

### Princípios Seguidos
- **SOLID**: Responsabilidade única, interfaces claras
- **DRY**: Meta_Extractor centraliza extração
- **Separation of Concerns**: Admin/Public/Ajax separados
- **Dependency Injection**: Via construtor

## Decisões Arquiteturais

### Por que Plugin Modular?
- Facilita manutenção e testes
- Permite extensibilidade
- Segue padrões WordPress

### Por que Autoloader Customizado?
- Sem dependências externas (Composer)
- Distribuição simplificada
- Adequado para plugin WordPress

### Por que WooCommerce API?
- Abstração sobre banco de dados
- Compatibilidade HPOS garantida
- Segurança built-in

## Melhorias Futuras

- Implementar paginação na tabela
- Adicionar cache para queries pesadas
- Sistema de eventos para extensibilidade
- API REST para integrações externas

## Recursos

- **Arquitetura**: `.context/docs/architecture.md`
- **Fluxo de Dados**: `.context/docs/data-flow.md`
