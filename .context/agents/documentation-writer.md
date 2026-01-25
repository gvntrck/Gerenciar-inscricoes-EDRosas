---
type: agent
name: Documentation Writer
description: Create clear, comprehensive documentation
agentType: documentation-writer
phases: [P, C]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Documentation Writer - Inscrições Pagas

## Responsabilidades

- Criar documentação clara e abrangente
- Manter documentação atualizada
- Escrever guias de usuário e desenvolvedor
- Documentar APIs e interfaces

## Documentação Existente

### Para Usuários
- `readme.md` - Uso básico do shortcode

### Para Desenvolvedores
- `novos-campos.md` - Guia completo para adicionar campos (413 linhas)
- `.context/docs/` - Documentação técnica completa
- `.context/agents/` - Playbooks de agentes

## Estrutura de Documentação

### README.md
- Visão geral do plugin
- Instalação e requisitos
- Uso básico
- FAQ

### Guias Técnicos
- Arquitetura do sistema
- Fluxo de dados
- Segurança
- Testes
- Ferramentas

### Guias de Desenvolvimento
- Adicionar novos campos
- Criar handlers AJAX
- Estender funcionalidades

## Melhores Práticas

### Clareza
- Linguagem simples e direta
- Exemplos de código práticos
- Screenshots quando apropriado

### Organização
- Estrutura hierárquica clara
- Links entre documentos relacionados
- Índice para documentos longos

### Manutenção
- Atualizar ao fazer mudanças
- Versionar documentação
- Marcar seções obsoletas

## Templates

### Novo Recurso
```markdown
# [Nome do Recurso]

## Visão Geral
[Descrição breve]

## Uso
[Exemplos práticos]

## Parâmetros
[Tabela de parâmetros]

## Exemplos
[Código de exemplo]
```

## Recursos

- **Projeto**: `.context/docs/project-overview.md`
- **Workflow**: `.context/docs/development-workflow.md`
