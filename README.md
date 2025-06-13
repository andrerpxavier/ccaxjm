
Este repositório contém a infraestrutura e o código da aplicação **Catálogo de Telemóveis**. O objetivo é disponibilizar, em máquinas virtuais na Cloud do Azure, uma aplicação PHP com base de dados MySQL que permite gerir informação sobre telemóveis, no contexto do projeto Final da cadeira Cloud Computing do ISTEC realizado pelos alunos:

2022001 - André Ferro Xavier
2022012 - João Eduardo Malhadinha

## Conteúdo
- **main.tf** – ficheiro Terraform responsável por criar os recursos necessários, nomeadamente grupo de recursos, rede virtual, máquinas virtuais (web e MySQL) e associações de segurança.
- **index.php**, **mysqli_connect.php** e **db_queries.php** – ficheiros PHP que compõem a aplicação Web.

## Pré‑requisitos
- [Azure CLI](https://learn.microsoft.com/cli/azure/install-azure-cli) instalado e configurado.
- [Terraform](https://developer.hashicorp.com/terraform/install) instalado na máquina local.
- Conta no Azure com permissões para criar recursos.

## Como utilizar
1. Autentique‑se na sua conta Azure:
   ```bash
   az login
   ```
2. Inicialize o Terraform dentro da pasta do projeto:
   ```bash
   terraform init
   ```
3. Aplique a configuração para criar todos os recursos (opção `-auto-approve` para não pedir confirmação):
   ```bash
   terraform apply -auto-approve
   ```

Após a conclusão, o Terraform irá apresentar o endereço IP público da máquina virtual Web. Utilize esse endereço no seu browser para aceder à aplicação.

Para remover todos os recursos criados, execute:
```bash
terraform destroy
```
