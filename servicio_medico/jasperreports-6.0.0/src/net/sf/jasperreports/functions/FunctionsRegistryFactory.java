/*
 * JasperReports - Free Java Reporting Library.
 * Copyright (C) 2001 - 2014 TIBCO Software Inc. All rights reserved.
 * http://www.jaspersoft.com
 *
 * Unless you have purchased a commercial license agreement from Jaspersoft,
 * the following license terms apply:
 *
 * This program is part of JasperReports.
 *
 * JasperReports is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * JasperReports is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with JasperReports. If not, see <http://www.gnu.org/licenses/>.
 */
package net.sf.jasperreports.functions;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import net.sf.jasperreports.engine.JRPropertiesMap;
import net.sf.jasperreports.engine.JRPropertiesUtil;
import net.sf.jasperreports.engine.JRPropertiesUtil.PropertySuffix;
import net.sf.jasperreports.extensions.DefaultExtensionsRegistry;
import net.sf.jasperreports.extensions.ExtensionsRegistry;
import net.sf.jasperreports.extensions.ExtensionsRegistryFactory;
import net.sf.jasperreports.extensions.SingletonExtensionRegistry;

/**
 * @author Massimo Rabbi (mrabbi@users.sourceforge.net)
 * @version $Id: FunctionsRegistryFactory.java 7197 2014-08-27 11:59:50Z teodord $
 */
public class FunctionsRegistryFactory  implements ExtensionsRegistryFactory
{
	/**
	 * The key used inside a jasperrpeorts_extensions.properties file to denote the classes
	 * referenced by this extension 
	 * @deprecated Replaced by {@link #FUNCTIONS_CLASSES_PROPERTY_PREFIX}.
	 */
	public final static String EXPRESSION_FUNCTIONS_CLASSES_PROPERTY_PREFIX = 
		DefaultExtensionsRegistry.PROPERTY_REGISTRY_PREFIX + "expression.functions.classes.";
	
	/**
	 * The key used to register the specific type of extension factory
	 * @deprecated To be removed.
	 */
	public final static String PROPERTY_EXPRESSION_FUNCTIONS_REGISTRY_FACTORY =
		DefaultExtensionsRegistry.PROPERTY_REGISTRY_FACTORY_PREFIX + "expression.functions";

	/**
	 * The key used inside a jasperreports_extensions.properties file to denote the classes
	 * referenced by this extension 
	 */
	public final static String FUNCTIONS_CLASSES_PROPERTY_PREFIX = 
		DefaultExtensionsRegistry.PROPERTY_REGISTRY_PREFIX + "functions.";
	
	/**
	 * 
	 */
	public ExtensionsRegistry createRegistry(String registryId, JRPropertiesMap properties)
	{
		List<String> classNames = new ArrayList<String>();

		addFunctionClasses(classNames, properties, EXPRESSION_FUNCTIONS_CLASSES_PROPERTY_PREFIX);
		addFunctionClasses(classNames, properties, FUNCTIONS_CLASSES_PROPERTY_PREFIX);
		
		return new SingletonExtensionRegistry<FunctionsBundle>(FunctionsBundle.class, new FunctionsBundle(classNames));
	}
	
	/**
	 * 
	 */
	private void addFunctionClasses(List<String> classNames, JRPropertiesMap properties, String propertyPrefix)
	{
		List<PropertySuffix> functionClassProperties = JRPropertiesUtil.getProperties(properties, propertyPrefix);
		for (Iterator<PropertySuffix> it = functionClassProperties.iterator(); it.hasNext();)
		{
			PropertySuffix functionsClassesProp = it.next(); 

			// We assume this property value is a comma-separated class names list like: a.b.c.ClassA, a.b.d.ClassB
			
			String[] classes = functionsClassesProp.getValue().split(",");
			
			for (String className : classes)
			{
				className = className.trim();
				if (className.length() > 0)
				{
					classNames.add( className);
				}
			}
		}
	}
	
}

