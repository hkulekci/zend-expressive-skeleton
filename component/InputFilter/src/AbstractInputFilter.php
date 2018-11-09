<?php
/**
 *
 * @since     Mar 2018
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace InputFilter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

abstract class AbstractInputFilter implements MiddlewareInterface
{
    public const ATTRIBUTE_NAME = 'input-filter-result';
    use BaseInputFilterTrait;
    use InputFilterHelperTrait;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return Response
     * @throws \Zend\InputFilter\Exception\RuntimeException
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params      = $request->getQueryParams();
        $data        = $request->getParsedBody();
        $result = [
            'queryParamsMessages' => [],
            'parsedBodyMessages'  => [],
            'filteredParsedBody'  => [],
            'filteredQueryParams' => [],
        ];

        if ($this->inputFilterSpecsForGetParameters()) {
            $inputFilter = $this->buildInputFilter($this->inputFilterSpecsForGetParameters());
            $inputFilter->setData($params);
            if (!$inputFilter->isValid()) {
                $result['queryParamsMessages'] = $inputFilter->getMessages();
            }
            $result['filteredQueryParams'] = $inputFilter->getValues();
        }
        if ($this->inputFilterSpecsForPostParameters()) {
            $inputFilter = $this->buildInputFilter($this->inputFilterSpecsForPostParameters());
            $inputFilter->setData((array) $data);
            if (!$inputFilter->isValid()) {
                $result['parsedBodyMessages'] = $inputFilter->getMessages();
            }
            $result['filteredParsedBody'] = $inputFilter->getValues();
        }

        $request = $request->withAttribute(self::ATTRIBUTE_NAME, $result);
        $request = $request->withQueryParams($result['queryParamsMessages'])->withParsedBody($result['filteredParsedBody']);

        return $handler->handle($request);
    }
}
