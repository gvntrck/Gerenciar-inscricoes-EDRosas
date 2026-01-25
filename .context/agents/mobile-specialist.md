---
type: agent
name: Mobile Specialist
description: Develop native and cross-platform mobile applications
agentType: mobile-specialist
phases: [P, E]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Mobile Specialist - Inscrições Pagas

## Contexto

O plugin **Inscrições Pagas** é um plugin WordPress web-based. Atualmente não possui aplicativo mobile nativo.

## Responsividade Web

A tabela de inscrições é acessível via navegadores mobile através de design responsivo CSS.

### Otimizações Mobile Existentes
- Layout adaptativo para telas pequenas
- Touch-friendly para interações
- Tabela com scroll horizontal em mobile

## Futuras Possibilidades Mobile

### App Nativo (Futuro)
Se houver necessidade de app mobile:

**React Native**:
- Compartilhar lógica entre iOS/Android
- Integrar via WooCommerce REST API
- Autenticação WordPress

**Flutter**:
- Performance nativa
- UI consistente
- Hot reload para desenvolvimento

### PWA (Progressive Web App)
- Converter interface web em PWA
- Funcionalidade offline
- Instalável em dispositivos
- Notificações push

## Integração API

Para app mobile, seria necessário:
1. Criar endpoints REST API customizados
2. Autenticação JWT
3. Sincronização de dados
4. Cache local

## Recursos

- **Frontend**: `.context/agents/frontend-specialist.md`
- **Arquitetura**: `.context/docs/architecture.md`
