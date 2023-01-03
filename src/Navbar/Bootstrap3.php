<?php


namespace BetoCampoy\ChampsFramework\Navbar;


class Bootstrap3 extends Navbar
{

    protected function bootstrap():string
    {

        $rootItens = $this->rootItems();

        $navbarItens = "";
        $navbarStart = "
            <nav class='navbar navbar-default'>
                <div class='container-fluid'>
                    <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#meu-nav-bar' aria-expanded='false'>
                        <span class='sr-only'>Toggle navigation</span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                        <span class='icon-bar'></span>
                    </button>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='/'><i class='glyphicon glyphicon-home'></i></a>
                    </div>
                    <div class='collapse navbar-collapse' id='meu-nav-bar' >
                        <ul class='nav navbar-nav'>
                        {{itens}}
            			</ul>
                    </div>
                </div>
            </nav>";

        if($rootItens->count() > 0) {
            foreach ($rootItens->fetch(true) as $parent) {
                $navbarItens .= $this->bootstrapItens($parent);
            }
        }
        else{
            $navbarItens = "não há itens cadastrados para navegação";
        }
        $navbarClose = "
            			</ul>
                    </div>
                </div>
            </nav>";


        return $navbarStart . $navbarItens . $navbarClose;
    }

}