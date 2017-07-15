<?php

use PhpParser\Node\Name;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Eval_;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\StaticCallVisitor;
use edsonmedina\php_testability\Contexts\RootContext;

require_once __DIR__ . '/../../vendor/autoload.php';

class StaticCallVisitorTest extends PHPUnit\Framework\TestCase
{
    /**
     * @covers \edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
     */
    public function testLeaveNodeWithDifferentType()
    {
        $context = new RootContext ('/');

        /** @var ContextStack|PHPUnit_Framework_MockObject $stack */
        $stack = $this->getMockBuilder(ContextStack::class)
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');

        $node = $this->getMockBuilder(Eval_::class)
            ->disableOriginalConstructor()
            ->getMock();

        $visitor = new StaticCallVisitor ($stack, $context);
        $visitor->leaveNode($node);
    }

    /**
     * @covers \edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
     */
    public function testLeaveNodeInGlobalSpace()
    {
        $context = new RootContext ('/');

        $stack = $this->getMockBuilder(ContextStack::class)
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');

        $node = $this->getMockBuilder(StaticCall::class)
            ->disableOriginalConstructor()
            ->getMock();

        $visitor = $this->getMockBuilder(StaticCallVisitor::class)
            ->setConstructorArgs([$stack, $context])
            ->setMethods(['inGlobalScope'])
            ->getMock();

        $visitor->expects($this->once())->method('inGlobalScope')->willReturn(true);
        $visitor->leaveNode($node);
    }

    public function testLeaveNodeWithParentCall()
    {
        $context = new RootContext ('/');

        $stack = $this->getMockBuilder(ContextStack::class)
            ->setConstructorArgs([$context])
            ->setMethods(['addIssue'])
            ->getMock();

        $stack->expects($this->never())->method('addIssue');
        /** @var \PhpParser\Node\Expr\StaticCall $node */
        $node = $this->getMockBuilder(StaticCall::class)
            ->disableOriginalConstructor()
            ->getMock();
        $node->class = $this->getMockBuilder(Name::class)->disableOriginalConstructor()->setMethods(['toString'])->getMock();
        $node->class->parts = ['parent'];
        $node->class->expects($this->once())->method('toString')->willReturn('parent');

        $visitor = $this->getMockBuilder(StaticCallVisitor::class)
            ->setConstructorArgs([$stack, $context])
            ->setMethods(['inGlobalScope'])
            ->getMock();

        $visitor->expects($this->once())->method('inGlobalScope')->willReturn(false);

        $visitor->leaveNode($node);
    }
}