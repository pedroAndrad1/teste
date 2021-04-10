<h2> Olá {{ $name }},</h2>
<p>
  Parece que você solicitou uma recuperação da sua senha, caso queira prosseguir clique no link abaixo:
</p>
<p>
    <a href="http://localhost:8080/reset?token={{$token}}">Recuperar Senha</a>
</p>
<p>
    Caso não tenha sido você quem fez a solicitação, favor descartar o e-mail.
</p>