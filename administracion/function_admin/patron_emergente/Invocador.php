<?php
namespace FunctionAdmin\PatronEmergente;

class Invocador {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function ejecutar() {
        $this->command->execute();
    }
}
?>
