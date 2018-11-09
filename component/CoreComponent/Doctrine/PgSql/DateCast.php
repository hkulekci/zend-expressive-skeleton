<?php
namespace CoreComponent\Doctrine\PgSql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * DateDiffFunction ::= "to_char" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class DateCast extends FunctionNode
{
    public $firstDateExpression;
    public $secondDateExpression;

    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondDateExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'to_char('.$this->firstDateExpression->dispatch($sqlWalker).', '.$this->secondDateExpression->dispatch($sqlWalker).')';
    }
}
