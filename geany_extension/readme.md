# Geany source editor support

define typescript subset syntax and compile, make, run commands.

## install geany extension

>edit filetype_extensions.conf file

>file location:  /usr/share/geany/filetype_extensions.conf 

```
[Extensions]
....
Typescript=*.ts;
....
[Groups]
....
Script=Graphviz;Typescript;
....

```

>copy this_repo/geany_extension/filetypes.Typescript.conf to /usr/share/geany folder
