<?php
namespace CoreComponent\Doctrine\MySql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateDiffFunction ::= "datecast" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class DateCast extends FunctionNode
{
    public $firstDateExpression  = null;
    public $secondDateExpression = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        $secondPart = $this->secondDateExpression->dispatch($sqlWalker);
        $secondPart = str_replace(['YYYY', 'MM', 'DD'], ['%Y', '%m', '%d'], $secondPart);

        return 'DATE_FORMAT('.$this->firstDateExpression->dispatch($sqlWalker).', '.$secondPart.')';
    }
}
