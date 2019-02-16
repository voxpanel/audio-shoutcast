# /etc/bashrc

# System wide functions and aliases
# Environment stuff goes in /etc/profile

# By default, we want this to get set.
# Even for non-interactive, non-login shells.
PS1="\u@\h [\w]# "

if [ $UID -gt 99 ] && [ "`id -gn`" = "`id -un`" ]; then
	umask 002
else
	umask 022
fi

# are we an interactive shell?
if [ "$PS1" ]; then
    case $TERM in
	xterm*)
		if [ -e /etc/sysconfig/bash-prompt-xterm ]; then
			PROMPT_COMMAND=/etc/sysconfig/bash-prompt-xterm
		else
	    	PROMPT_COMMAND='echo -ne "\033]0;${USER}@${HOSTNAME%%.*}:${PWD/#$HOME/~}"; echo -ne "\007"'
		fi
		;;
	screen)
		if [ -e /etc/sysconfig/bash-prompt-screen ]; then
			PROMPT_COMMAND=/etc/sysconfig/bash-prompt-screen
		else
		PROMPT_COMMAND='echo -ne "\033_${USER}@${HOSTNAME%%.*}:${PWD/#$HOME/~}"; echo -ne "\033\\"'
		fi
		;;
	*)
		[ -e /etc/sysconfig/bash-prompt-default ] && PROMPT_COMMAND=/etc/sysconfig/bash-prompt-default
	    ;;
    esac
    # Turn on checkwinsize
    shopt -s checkwinsize
    [ "$PS1" = "\\s-\\v\\\$ " ] && PS1="[\u@\h \W]\\$ "
fi

if ! shopt -q login_shell ; then # We're not a login shell
	# Need to redefine pathmunge, it get's undefined at the end of /etc/profile
    pathmunge () {
		if ! echo $PATH | /bin/egrep -q "(^|:)$1($|:)" ; then
			if [ "$2" = "after" ] ; then
				PATH=$PATH:$1
			else
				PATH=$1:$PATH
			fi
		fi
	}

	# Only display echos from profile.d scripts if we are no login shell
    # and interactive - otherwise just process them to set envvars
    for i in /etc/profile.d/*.sh; do
        if [ -r "$i" ]; then
            if [ "$PS1" ]; then
                . $i
            else
                . $i >/dev/null 2>&1
            fi
        fi
    done

	unset i
	unset pathmunge
fi
# vim:ts=4:sw=4

alias which="type -path"
export EDITOR="pico"
export VISUAL="pico"

alias wtf="watch -n 1 w -hs"
alias wth="ps -uxa | more"
# Now for the dos users
alias dir="ls"
alias copy="cp"
alias del="rm"
alias deltree="rm -r"
alias move="mv"
alias ff="whereis"
alias attrib="chmod"
alias edit="pico"
alias chdir="cd"
alias mem="top"
alias search="grep"
alias pico="pico -w -z"


LS_OPTIONS='--color=tty -F -a -b -T 0 -l -h';
export LS_OPTIONS;
alias ls='/bin/ls $LS_OPTIONS';
alias dir='/bin/ls $LS_OPTIONS --format=vertical';
alias vdir='/bin/ls $LS_OPTIONS --format=long';
alias d=dir;
alias v=vdir;
eval `dircolors -b`

#unlimit so we can run the whoami
ulimit -n 4096 -u 14335 -m unlimited -d unlimited -s 8192 -c 1000000 -v unlimited 2>/dev/null

LIMITUSER=$USER
if [ -e "/usr/bin/whoami" ]; then
        LIMITUSER=`/usr/bin/whoami`
fi
if [ "$LIMITUSER" != "root" ]; then
        ulimit -n 100 -u 20 -m 200000 -d 200000 -s 8192 -c 200000 -v 200000 2>/dev/null
else
        ulimit -n 4096 -u 14335 -m unlimited -d unlimited -s 8192 -c 1000000 -v unlimited 2>/dev/null
fi
