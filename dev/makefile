# Makefile for helping with your Magento extension
#
#  - install: Install Magento with the extension in it for testing purposes
#  - tar/tar.bz2/tar.gz/tar.xz/zip: Create an archive containing the extension
#  - phpdoc/phpdoc.xml.dist: Generate some API-Doc built by doc-comments
#
# It requires:
#
#  - The bash shell
#  - [magerun] (https://github.com/netz98/n98-magerun)
#
# The extension name will be retrieved from the basename of the folder where the project is.
# The means when you are using "/foo/bar/dev/makefile" then the project name is assumed as "bar".

SHELL := /bin/bash
DIR = $(shell pwd)
TAR = @cd $(projectRoot)/src && /bin/tar -caf $(DIR)/$(tarName) --exclude-vcs *

projectRoot = $(shell readlink -m $(DIR)/../)
projectName = $(shell basename $(projectRoot))
projectClone = $(installationFolder)/.modman/$(projectName)

magentoVersionByName = "magento-ce-$(magentoVersion)"

# versions provided by magerun
magentoVersion = 1.8.1.0
mage-1810: magentoVersion = 1.8.1.0
mage-1800: magentoVersion = 1.8.0.0
mage-1702: magentoVersion = 1.7.0.2
mage-1620: magentoVersion = 1.6.2.0
mage-1510: magentoVersion = 1.5.1.0
mage-1510: magentoVersionByName = magento-mirror-$(magentoVersion)

makeFeatureList = php makeFeatureList.php

# tar names
tarName = $(projectName).$@

testdoxFileRelative = var/phpunit/testdox.txt
testdoxFile = $(installationFolder)/$(testdoxFileRelative)

SHORTVERSION= $(subst .,,$(magentoVersion))
installationFolder=$(shell readlink -m $(DIR)/../tests/mage-$(SHORTVERSION))

define newline


endef

phpdocDistXmlFile = $(projectRoot)/doc/phpdoc.dist.xml
define phpdocDistXml
<?xml version="1.0" encoding="UTF-8" ?>
<phpdoc>
	<title>$(projectName)</title>
	<parser>
		<target>phpdoc</target>
		<markers>
			<item>TODO</item>
			<item>FIXME</item>
		</markers>
		<extensions>
			<extension>php</extension>
			<extension>php3</extension>
			<extension>phtml</extension>
		</extensions>
	</parser>
	<transformer>
		<target>output</target>
		<external-class-documentation>
			<prefix>$(projectName)</prefix>
		</external-class-documentation>
	</transformer>
	<transformations>
		<template name="responsive"/>
	</transformations>
	<files>
		<directory>src</directory>
	</files>
</phpdoc>
endef
phpdocDistXml:=$(subst $(newline),\n,${phpdocDistXml})

all: install phpdoc

ci:


featureList:
	@echo Using "tests/mage-$(SHORTVERSION)" as base ...
ifneq '$(wildcard $(testdoxFile))' ''
	@cd $(projectRoot)/doc && $(makeFeatureList) -f $(testdoxFile)
else
	@echo Run UnitTest with testdox first. Need the file "$(testdoxFileRelative)"
endif

# Install the latest possible magento version
install:
	@echo Installing $(magentoVersion) ...
	@mkdir -p $(installationFolder)
	@magerun install \
	 --installSampleData=yes \
	 --useDefaultConfigParams=yes \
	 --magentoVersionByName=$(magentoVersionByName) \
	 --installationFolder=$(installationFolder)
	@cp $(installationFolder)/errors/local.xml.sample $(installationFolder)/errors/local.xml
	@cd $(installationFolder) && magerun config:set "dev/template/allow_symlink" 1
	@cd $(installationFolder) && modman init && ln -s $(projectRoot) $(projectClone) && modman deploy-all

	@cd $(installationFolder) && modman clone https://github.com/magento-hackathon/MageBehat
	@cd $(installationFolder)/.modman/MageBehat && composer install

	@cd $(installationFolder) && modman clone https://github.com/EcomDev/EcomDev_PHPUnit.git --branch dev
	@cd $(installationFolder)/shell && php ecomdev-phpunit.php \
										-a magento-config \
										--base-url http://127.0.0.1/ \
										--db-name $(projectName)_test$(SHORTVERSION)
	@sed -i 's/<\/logging>/    <log type="testdox-text" target="var\/phpunit\/testdox.txt"\/>\n    <\/logging>/' \
		$(installationFolder)/phpunit.xml.dist
	@echo "Please update the information in $(installationFolder)/app/etc/local.xml.phpunit"

mage-1810: install
mage-1800: install
mage-1702: install
mage-1620: install
mage-1510: install

phpdoc: phpdoc.dist.xml
	@phpdoc -c $(projectRoot)/doc/phpdoc.dist.xml -t $(projectRoot)/doc/api -d $(projectRoot)/src

phpdoc.dist.xml:
	@mkdir -p $(projectRoot)/doc
ifneq '$(wildcard $(phpdocDistXmlFile))' ''
	@echo "Won't overwrite existing: $(projectRoot)/doc/phpdoc.dist.xml"
else
	@echo -e '$(phpdocDistXml)' > $(projectRoot)/doc/phpdoc.dist.xml
endif

tar:
	$(TAR)

tar.bz2:
	$(TAR) -j

tar.gz:
	$(TAR) -z

tar.xz:
	@$(TAR) -J

zip:
	@zip -r $(projectName).zip $(projectRoot)/src/* -x "*/.git*"
