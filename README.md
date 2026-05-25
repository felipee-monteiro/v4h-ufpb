## Arquitetura

Neste projeto, utilizei Laravel (MVC), Vue.js e PostgreSQL, adotando uma abordagem híbrida. O Vue roda com suporte do Inertia, enquanto o backend serve o frontend, integrando ambos de forma coesa.

Essa escolha traz diversas vantagens: aproveitamos todo o ecossistema Laravel, mantemos um monolito simplificando o deploy, economizamos recursos ao evitar múltiplos servidores, facilitamos o empacotamento com um único Dockerfile e aceleramos a produtividade ao aproveitar recursos prontos do Laravel, como o scaffolding de autenticação.

---

## Trade-offs

A principal decisão foi o uso de um banco relacional. Os requisitos da aplicação exigiam fortes relações entre dados, como a conexão entre solicitações, solicitantes e médicos.

Um banco relacional, como o PostgreSQL, foi escolhido porque, além de expressar naturalmente essas relações, ele oferece alta performance na leitura e escrita.

Escolhi o PostgreSQL e não o MongoDB porque bancos orientados a documentos não representam tão naturalmente relações complexas. O PostgreSQL, por outro lado, permite consultas complexas e integridade referencial, garantindo maior consistência do modelo de dados.

Além disso, optei pelo Vue em vez do React pela simplicidade. O Vue entrega reatividade e estrutura suficiente para aplicações modernas sem adicionar complexidade desnecessária, o que ajudou a manter o projeto mais direto e escalável.

Como a aplicação não terá volumes extremos de escrita, o PostgreSQL se destaca, oferecendo robustez e escalabilidade, com suporte a réplicas para crescimento futuro.

---

## Decisões de Design de Código

Nas decisões de design, priorizei o baixo acoplamento, o que foi fundamental para facilitar refatorações ao longo do projeto.

Apliquei o princípio SOLID, com destaque para o Single Responsibility Principle, garantindo que cada classe ou módulo tivesse uma única responsabilidade.

Além disso, utilizei composição no lugar de herança, especialmente com composables no Vue e actions no Laravel, reforçando o isolamento de responsabilidades.

Esse foco em baixo acoplamento não só facilitou manutenção e evolução do código, como também tornou o sistema altamente testável, permitindo testes mais isolados e confiáveis.

---

## Ferramentas de IA Utilizadas

Para auxiliar no desenvolvimento, utilizei dois modelos de IA principais: o Codex, do ChatGPT, e o Copilot, da Microsoft.

Após testes, o Codex se destacou, principalmente na construção do dashboard quando combinado com MCP para fornecer contexto e com RAG para integrar informações do projeto.

No entanto, ambas as IAs apresentaram um problema recorrente: duplicação de código. Mesmo com contexto, nem sempre reutilizam corretamente estruturas existentes.

Nesse ponto, o baixo acoplamento do sistema fez diferença: como o código foi projetado de forma modular, a manutenção do código gerado por IA se tornou mais simples e previsível.

Isso tornou o ciclo de revisão, teste e refatoração muito mais eficiente.

O copilot se destacou na geração de testes automatizados, com contextos via MCP e RAG.

---

## Limitações e Próximos Passos para Produção

No ambiente de produção, uma das principais melhorias será a aplicação de índices no banco de dados.

Embora o volume de escrita atual não seja alto, a projeção futura inclui milhões de registros, o que exige otimização de consultas.

Isso se alinha ao uso de réplicas no PostgreSQL e escalabilidade horizontal com balanceamento de carga via nginx.

### Funcionalidades ainda não implementadas

- **Notificações em tempo real**
    - Planejadas com SSE (Server-Sent Events)
    - Conexão unidirecional mais leve
    - Possível desafio: escalabilidade com muitas conexões abertas

- **Análise de documentos com IA**
    - Processamento assíncrono via filas (Redis ou RabbitMQ)
    - Evita bloqueio da requisição principal
    - Permite maior escalabilidade

- **Upload de arquivos**
    - Uso de AWS S3 ou serviço equivalente
    - Upload multipart com retomada em caso de falha
    - Melhora experiência e confiabilidade

---

## Avaliação Honesta

Apesar de não ter implementado todas as funcionalidades planejadas, foquei em construir uma base sólida.

O principal foco foi a implementação de testes automatizados para fluxos críticos, garantindo segurança para futuras refatorações.

Nem todos os edge cases foram cobertos, o que é uma limitação reconhecida.

Ainda assim, a base construída permite evolução contínua com segurança, previsibilidade e baixo custo de manutenção.

## Instruções de Execução

1. Clone o repositório.

2. Configure as variáveis de ambiente:

    ```bash
    cp .env.example .env
    ```

3. Suba os containers:

    ```bash
    docker compose up -d
    ```

4. Entre no container da aplicação:

    ```bash
    docker exec -it v4h-felipe-monteiro-php /bin/bash
    ```

5. Instale as dependências:

    ```bash
    composer install
    ```

    ```bash
    npm ic
    ```

6. Gere a chave da aplicação:

    ```bash
    pa key:generate
    ```

7. Execute as migrations:

    ```bash
    pa migrate --seed
    ```

8. Gere os artefatos do front-end:

    ```bash
    npm run build
    ```

O projeto estará rodando em http://localhost:3030

### Testando a aplicação (Testes automatizados)

Ainda dentro do container, execute:

```bash
pa test
```

### Instruções de como testar manualmente:

#### Credenciais do solicitante:

email: test@example.com
senha: password

#### Credenciais do médico:

email: cardiologia@example.com
senha: password

email: cirurgiao@example.com
senha: password

email: dentista@example.com
senha: password

email: doencasraras@example.com
senha: password

email: oxigenoterapia@example.com
senha: password

Fluxo Solicitante:

1 - Faça login como solicitante;
2 - Você será redirecionado para o dashboard, onde poderá testar cadastro de teleconsultas, filros, etc.
3 - Quando for criar uma nova teleconsulta, especifique a especialidade para mover para o médico responsavel.

Fluxo médico responsável:

1 - Faça login como médico da especialidade na qual a teleconsulta foi criada;
2 - Visualize a teleconsulta;
3 - Registre um parecer;
