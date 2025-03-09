- Flow

Pipelinenya:
- build docker image (app dan web server)
- push docker image ke docker hub
- host server ngepull docker image
- docker compose di server

Build
Push
Pull
Deploy

- Command Line Notes :

Build Docker Image "docker build -f Docker/dockerfile/app.Dockerfile -t sipta-app:prod ." (ganti ke path yg valid)