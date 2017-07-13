<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticCallVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class StaticCallVisitorTest extends PHPUnit\Framework\TestCase
{
    /**
     * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
     */
    public function testLeaveNodeWithDifferentType()
    {
        $context = new RootContext ('/');

        $stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');

        $wrongNode = $this->getMockBuilder('PhpParser\Node\Expr\StaticCall')
            ->disableOriginalConstructor()
            ->getMock();

        $node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
            ->disableOriginalConstructor()
            ->getMock();

        $visitor = new StaticCallVisitor ($stack, $context);
        $visitor->leaveNode($node);
    }

    /**
     * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
     */
    public function testLeaveNodeInGlobalSpace()
    {
        $context = new RootContext ('/');

        $stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');

        $node = $this->getMockBuilder('PhpParser\Node\Expr\StaticCall')
            ->disableOriginalConstructor()
            ->getMock();

        $visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\StaticCallVisitor')
            ->setConstructorArgs([$stack, $context])
            ->setMethods(['inGlobalScope'])
            ->getMock();

        $visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
        $visitor->leaveNode($node);
    }

    public function testLeaveNodeWithParentCall()
    {
        $context = new RootContext ('/');

        $stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');
        /** @var \PhpParser\Node\Expr\StaticCall $node */
        $node = $this->getMockBuilder('PhpParser\Node\Expr\StaticCall')
            ->disableOriginalConstructor()
            ->getMock();
        $node->class = $this->getMockBuilder('PhpParser\Node\Name')->disableOriginalConstructor()->setMethods(['toString'])->getMock();
        $node->class->parts = ['parent'];
        $node->class->expects($this->once())->method('toString')->willReturn('parent');

        $visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\StaticCallVisitor')
            ->setConstructorArgs([$stack, $context])
            ->setMethods(['inGlobalScope'])
            ->getMock();

        $visitor->expects($this->once())->method('inGlobalScope')->willReturn(false);

        $visitor->leaveNode($node);
    }
}