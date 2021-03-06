package com.swe.fairurban.Helpers;

public class ServiceHelper {
	
	private final static String MAIN_SERVICE_HOST = "http://swe.cmpe.boun.edu.tr:8180/rest";
	
	

	private final static String LIST_DATA_URL = "/service/entries";
	
	private final static String LOGIN_URL = "/service/login/sigin";
	
	private final static String INSERT_DATA_URL = "/service/entries/add";
	
	private final static String GET_CATEGORIES_URL = "/service/categories/";
	
	private final static String VOTE_URL = "/service/entries/thumbs";
	
	private final static String MARK_FIXED_URL = "/service/entries/edit/status";
	
	private final static String EDIT_ENTRY_URL = "/service/entries/edit";
	
	public final static String IMAGES_FOLDER = "http://testpalette.com/";
	
	
	public static String GetEntiryDetailsUrl(Integer entityId)
	{
		return MAIN_SERVICE_HOST + String.format(LIST_DATA_URL) + "/" + entityId;
	}
	
	public static String GetVotingUrl()
	{
		return MAIN_SERVICE_HOST + VOTE_URL;
	}
	

	public static String GetEditEtryUrl()
	{
		return MAIN_SERVICE_HOST + EDIT_ENTRY_URL;
	}
	
	
	
	public static String GetMarFixedUrl()
	{
		return MAIN_SERVICE_HOST + MARK_FIXED_URL;
	}
	
	public static String GetListDataUrl()
	{
		return MAIN_SERVICE_HOST + String.format(LIST_DATA_URL);
	}
	
	public static String GetListDataForCategoryUrl(Integer subCategoryId)
	{
		return MAIN_SERVICE_HOST + String.format(LIST_DATA_URL) + "?categoryId=" + subCategoryId;
	}
	
	public static String GetMainCategoriesUrl()
	{
		return MAIN_SERVICE_HOST + GET_CATEGORIES_URL;
	}
	
	public static String GetSubCategoriesUrl(Integer parentCategoryId)
	{
		return MAIN_SERVICE_HOST + GET_CATEGORIES_URL + parentCategoryId;
	}
	
	public static String GetInsertDataUrl()
	{
		return MAIN_SERVICE_HOST + INSERT_DATA_URL;
	}
	
	public static String GetLoginUrl()
	{
		return MAIN_SERVICE_HOST + LOGIN_URL;
	}
	
}
