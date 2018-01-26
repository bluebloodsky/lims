<?php
/*
 * Copyright (c) KUBO Atsuhiro <kubo@iteman.jp> and contributors,
 * All rights reserved.
 *
 * This file is part of Workflower.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\Workflower\Workflow\Element;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Entity\Operation\EquatableInterface;
use PHPMentors\DomainKata\Entity\Operation\IdentifiableInterface;

interface WorkflowElementInterface extends EntityInterface, EquatableInterface, IdentifiableInterface
{
    /**
     * @return string
     */
    public function getName();
}