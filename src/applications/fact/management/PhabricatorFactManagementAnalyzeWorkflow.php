<?php

/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

final class PhabricatorFactManagementAnalyzeWorkflow
  extends PhabricatorFactManagementWorkflow {

  public function didConstruct() {
    $this
      ->setName('analyze')
      ->setSynopsis(pht('Manually invoke fact analyzers.'))
      ->setArguments(array());
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();

    $daemon = new PhabricatorFactDaemon(array());
    $daemon->setVerbose(true);
    $daemon->setEngines(PhabricatorFactEngine::loadAllEngines());

    $iterators = array(
      new PhabricatorFactUpdateIterator(new DifferentialRevision()),
    );

    foreach ($iterators as $iterator) {
      $daemon->processIterator($iterator);
    }

    return 0;
  }

}