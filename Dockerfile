# Use a imagem base que você deseja
FROM php:8.1-apache

# Instale as dependências (se necessário)
# RUN apt-get update && apt-get install -y ...

# Copiar o entrypoint.sh para o contêiner
COPY ./.docker/entrypoint.sh /entrypoint.sh

# Tornar o script executável
RUN chmod +x /entrypoint.sh

# Definir o ENTRYPOINT
ENTRYPOINT ["/entrypoint.sh"]
