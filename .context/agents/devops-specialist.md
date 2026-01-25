---
type: agent
name: Devops Specialist
description: Design and maintain CI/CD pipelines
agentType: devops-specialist
phases: [E, C]
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# DevOps Specialist - Inscrições Pagas

## Responsabilidades

- Configurar pipelines CI/CD
- Automatizar testes e deploy
- Gerenciar ambientes (dev/staging/prod)
- Monitorar saúde do plugin

## Deploy Atual

**Manual**:
1. Atualizar versão em `inscricoes-pagas.php`
2. Testar em ambiente local
3. Fazer commit e push
4. Upload manual via FTP/SFTP ou painel WordPress

## CI/CD Recomendado

### GitHub Actions
```yaml
name: CI
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: PHP Syntax Check
        run: find . -name "*.php" -exec php -l {} \;
      - name: Run Tests
        run: composer test
```

### Deploy Automático
```yaml
name: Deploy
on:
  push:
    tags:
      - 'v*'
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Create Release
        uses: actions/create-release@v1
      - name: Deploy to WordPress.org
        run: ./deploy.sh
```

## Ambientes

- **Local**: Desenvolvimento ativo
- **Staging**: Testes pré-produção
- **Production**: Site live

## Monitoramento

- Logs de erro WordPress
- Performance de queries
- Taxa de erro em AJAX
- Uso de recursos

## Recursos

- **Ferramentas**: `.context/docs/tooling.md`
- **Workflow**: `.context/docs/development-workflow.md`
