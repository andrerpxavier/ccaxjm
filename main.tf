# ------------------------------
# Configuração do Terraform e Providers
# ------------------------------
terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~> 3.0"
    }
    template = {
      source = "hashicorp/template"
      version = "~> 2.2"
    }
  }
}

provider "azurerm" {
  features {}
  subscription_id = var.subscription_id
}

variable "location" {
  default = "South Africa North"
}

resource "azurerm_resource_group" "main" {
  name     = "projeto-final-CloudComputing"
  location = var.location
}

resource "azurerm_virtual_network" "vnet" {
  name                = "vnet-telemoveis"
  address_space       = ["10.0.0.0/16"]
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name
}

resource "azurerm_subnet" "public" {
  name                 = "public"
  resource_group_name  = azurerm_resource_group.main.name
  virtual_network_name = azurerm_virtual_network.vnet.name
  address_prefixes     = ["10.0.1.0/24"]
}

resource "azurerm_subnet" "private" {
  name                 = "private"
  resource_group_name  = azurerm_resource_group.main.name
  virtual_network_name = azurerm_virtual_network.vnet.name
  address_prefixes     = ["10.0.2.0/24"]
}

resource "azurerm_public_ip" "web" {
  name                = "web-ip"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name
  allocation_method   = "Static"
  sku                 = "Basic"
}

resource "azurerm_network_interface" "nic_mysql" {
  name                = "nic-mysql"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name

  ip_configuration {
    name                          = "internal"
    subnet_id                     = azurerm_subnet.private.id
    private_ip_address_allocation = "Dynamic"
  }
}

resource "azurerm_network_interface" "nic_web" {
  name                = "nic-web"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name

  ip_configuration {
    name                          = "public"
    subnet_id                     = azurerm_subnet.public.id
    private_ip_address_allocation = "Dynamic"
    public_ip_address_id          = azurerm_public_ip.web.id
  }
}

resource "azurerm_network_security_group" "nsg_web" {
  name                = "nsg-web"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name

  security_rule {
    name                       = "HTTP"
    priority                   = 100
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "80"
    source_address_prefix      = "*"
    destination_address_prefix = "*"
  }

  security_rule {
    name                       = "SSH"
    priority                   = 110
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "22"
    source_address_prefix      = "*"
    destination_address_prefix = "*"
  }
}

resource "azurerm_network_security_group" "nsg_mysql" {
  name                = "nsg-mysql"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name

  security_rule {
    name                       = "MySQL"
    priority                   = 100
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "3306"
    source_address_prefix      = "10.0.1.0/24"
    destination_address_prefix = "*"
  }

  security_rule {
    name                       = "SSH"
    priority                   = 110
    direction                  = "Inbound"
    access                     = "Allow"
    protocol                   = "Tcp"
    source_port_range          = "*"
    destination_port_range     = "22"
    source_address_prefix      = "*"
    destination_address_prefix = "*"
  }
}

resource "azurerm_subnet_network_security_group_association" "web_assoc" {
  subnet_id                 = azurerm_subnet.public.id
  network_security_group_id = azurerm_network_security_group.nsg_web.id
}

resource "azurerm_subnet_network_security_group_association" "mysql_assoc" {
  subnet_id                 = azurerm_subnet.private.id
  network_security_group_id = azurerm_network_security_group.nsg_mysql.id
}

resource "azurerm_linux_virtual_machine" "mysql_vm" {
  name                = "vm-mysql"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name
  size                = "Standard_B1s"
  admin_username      = "azureuser"
  admin_password      = "Projecto321"
  disable_password_authentication = false
  network_interface_ids = [azurerm_network_interface.nic_mysql.id]

  os_disk {
    caching              = "ReadWrite"
    storage_account_type = "Standard_LRS"
  }

  source_image_reference {
    publisher = "Canonical"
    offer     = "0001-com-ubuntu-server-jammy"
    sku       = "22_04-lts"
    version   = "latest"
  }

  custom_data = base64encode(<<EOF
#!/bin/bash
apt update -y
apt install mysql-server -y
systemctl start mysql
systemctl enable mysql
sed -i 's/^bind-address.*/bind-address = 0.0.0.0/' \
    /etc/mysql/mysql.conf.d/mysqld.cnf
systemctl restart mysql

mysql -u root <<EOSQL
CREATE DATABASE telemoveis_bd;
USE telemoveis_bd;
CREATE TABLE telemoveis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  marca VARCHAR(50),
  modelo VARCHAR(50),
  preco DECIMAL(10,2),
  armazenamento INT,
  ram INT,
  sistema_operativo VARCHAR(50)
);
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '1234';
CREATE USER 'root'@'10.0.1.%' IDENTIFIED WITH mysql_native_password BY '1234';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'10.0.1.%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOSQL
EOF
  )
}

resource "azurerm_linux_virtual_machine" "web_vm" {
  name                = "vm-web"
  location            = var.location
  resource_group_name = azurerm_resource_group.main.name
  size                = "Standard_B1s"
  admin_username      = "azureuser"
  admin_password      = "Projecto321"
  disable_password_authentication = false
  network_interface_ids = [azurerm_network_interface.nic_web.id]

  os_disk {
    caching              = "ReadWrite"
    storage_account_type = "Standard_LRS"
  }

  source_image_reference {
    publisher = "Canonical"
    offer     = "0001-com-ubuntu-server-jammy"
    sku       = "22_04-lts"
    version   = "latest"
  }

  custom_data = base64encode(<<CUSTOM_DATA
#!/bin/bash
apt update -y
apt install apache2 php php-mysqli git -y
cd /var/www/html
rm -rf *

# Copia os ficheiros PHP do repositório indicado
git clone https://github.com/andrerpxavier/ccaxjm .

# Atualiza o ficheiro de ligação à base de dados com o IP privado do MySQL
sed -i "s/\$host = '[^']*';/\$host = '${azurerm_network_interface.nic_mysql.private_ip_address}';/" mysqli_connect.php

systemctl restart apache2

sleep 10 

CUSTOM_DATA
  )
}

output "web_public_ip" {
  value       = azurerm_public_ip.web.ip_address
  description = "IP público da VM Web"
}

output "mysql_private_ip" {
  value       = azurerm_network_interface.nic_mysql.private_ip_address
  description = "IP privado da VM MySQL"
}
