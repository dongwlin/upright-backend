FROM scratch

COPY ./bin/upright-backend /

EXPOSE 8080

CMD [ "/upright-backend", "serve"]