package com.swe.accessibility.dataaccess;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.swe.accessibility.domain.Entry;
import com.swe.accessibility.domain.Priority;
import com.swe.accessibility.domain.SubReason;
import com.swe.accessibility.domain.User;

@Component
public class EntryDaoImpl implements EntryDao {

	@Autowired
	private SessionFactory sessionFactory;

	@Autowired
	private UserDao userDao;

	@Override
	public int insert(Entry entry) {

		return (Integer) sessionFactory.getCurrentSession().save(entry);
	}

	@Override
	public void delete(Entry entry) {

		sessionFactory.getCurrentSession().delete(entry);

	}

	@Override
	public void update(Entry entry) {

		sessionFactory.getCurrentSession().update(entry);
	}

	@Override
	public Entry getById(int id) {

		return (Entry) sessionFactory.getCurrentSession().get(Entry.class, id);
	}
	
	@Override
	public Entry getByIdFetchExtra(int id) {

		Session session = sessionFactory.getCurrentSession();
		Query query = session.createQuery("FROM Entry as e LEFT JOIN FETCH e.entryReasons where e.id = :id");
		query.setParameter("id", id);
		
		
		
		return (Entry) query.list().get(0);
	}
	

	@Override
	public List<Entry> get(BigDecimal coordX, BigDecimal coordY) {

		String queryStr = null;
		Query query = null;

		if (coordX == null && coordY != null) {
			queryStr = "from Entry where coordY =:y";
			query = sessionFactory.getCurrentSession().createQuery(queryStr);
			query.setParameter("y", coordY);
		}

		if (coordX != null && coordY == null) {
			queryStr = "from Entry where coordX = :x";
			query = sessionFactory.getCurrentSession().createQuery(queryStr);
			query.setParameter("x", coordX);

		}

		if (coordX != null && coordY != null) {
			queryStr = "from Entry where coordX = :x and coordY =:y";
			query = sessionFactory.getCurrentSession().createQuery(queryStr);
			query.setParameter("x", coordX);
			query.setParameter("y", coordY);
		}

		return query.list();

	}

	@Override
	public List<Entry> getAll() {

		Query query = sessionFactory.getCurrentSession().createQuery(
				"from Entry");
		return query.list();

	}

	@Override
	public List<Entry> getEntriesByType(String id) {

		String queryStr = "SELECT e.id,e.comment,e.coordX,e.coordY,e.upVoteCount,e.imageMeta,e.downVoteCount,e.imageMeta,e.priority,u.username from ViolationType v  join ReasonViolationType rv on rv.ViolationTypeId = v.id join Reason r on rv.ReasonId = r.id join EntryReason er on er.ReasonId = r.id join Entry e on er.EntryId = e.id "
				+ "join User u on e.submittedBy = u.id where v.id = :id";
		Query query = sessionFactory.getCurrentSession().createSQLQuery(
				queryStr);
		query.setParameter("id", id);

		Iterator<Object[]> iter = query.list().iterator();
		
		List<Entry> entries = new ArrayList<Entry>();
		while (iter.hasNext()) {
			Object[] arr = iter.next();
			Entry entr = new Entry();
			entr.setId((Integer) arr[0]);
			entr.setComment((String) arr[1]);
			entr.setCoordX((BigDecimal) arr[2]);
			entr.setCoordY((BigDecimal) arr[3]);
			entr.setUpVoteCount((Integer) arr[4]);
			entr.setDownVoteCount((Integer) arr[5]);
			entr.setImageMeta((String) arr[6]);
			entr.setPriority((Integer) arr[7]);
			entr.setUser(new User(arr[8].toString()));

			entries.add(entr);
		}
		return entries;
	}

	@Override
	public List<Entry> getEntriesByCategory(SubReason reason) {

		String queryStr = null;
		int id = 0;
		// Checking if reason is main or SubReason
		if (reason.getId() == reason.getParentReasonId()) {
			queryStr = "SELECT e.id,e.comment,e.coordX,e.coordY,e.upVoteCount,e.downVoteCount,e.imageMeta,e.priority,u.username from Reason r  join EntryReason er on er.ReasonId = r.id join Entry e on er.EntryID = e.id  "
					+ "join User u on e.submittedBy = u.id where r.parentReasonId = :id";
			id = reason.getParentReasonId();
		} else {
			queryStr = "SELECT e.id,e.comment,e.coordX,e.coordY,e.upVoteCount,e.downVoteCount,e.imageMeta,e.priority,u.username from Reason r  join EntryReason er on er.ReasonId = r.id join Entry e on er.EntryID = e.id  "
					+ "join User u on e.submittedBy = u.id where r.id = :id";
			id = reason.getId();
		}

		Query query = sessionFactory.getCurrentSession().createSQLQuery(
				queryStr);
		query.setParameter("id", id);
		Iterator<Object[]> iter = query.list().iterator();

		List<Entry> entries = new ArrayList<Entry>();
		while (iter.hasNext()) {
			Object[] arr = iter.next();
			Entry entr = new Entry();
			entr.setId((Integer) arr[0]);
			entr.setComment((String) arr[1]);
			entr.setCoordX((BigDecimal) arr[2]);
			entr.setCoordY((BigDecimal) arr[3]);
			entr.setUpVoteCount((Integer) arr[4]);
			entr.setDownVoteCount((Integer) arr[5]);
			entr.setImageMeta((String) arr[6]);
			entr.setPriority((Integer) arr[7]);
			entr.setUser(new User(arr[8].toString()));

			entries.add(entr);
		}
		return entries;
	}

	@Override
	public List<Entry> getEntriesByPriority(String priority) {
		
		int code = 0;
		
		if (priority.equals(Priority.LOW.getLabel()))
			code = Priority.LOW.getDegree();
		else
			code = Priority.CRITICAL.getDegree();
		String queryStr = "SELECT e.id,e.comment,e.coordX,e.coordY,e.upVoteCount,e.downVoteCount,e.imageMeta,e.priority,u.username from Reason r  join EntryReason er on er.ReasonId = r.id join Entry e on er.EntryID = e.id  "
				+ "join User u on e.submittedBy = u.id where e.priority = :priority";
		Query query = sessionFactory.getCurrentSession().createSQLQuery(
				queryStr);
		query.setParameter("priority", code);
		
		Iterator<Object[]> iter = query.list().iterator();

		List<Entry> entries = new ArrayList<Entry>();
		while (iter.hasNext()) {
			Object[] arr = iter.next();
			Entry entr = new Entry();
			entr.setId((Integer) arr[0]);
			entr.setComment((String) arr[1]);
			entr.setCoordX((BigDecimal) arr[2]);
			entr.setCoordY((BigDecimal) arr[3]);
			entr.setUpVoteCount((Integer) arr[4]);
			entr.setDownVoteCount((Integer) arr[5]);
			entr.setImageMeta((String) arr[6]);
			entr.setPriority((Integer) arr[7]);
			entr.setUser(new User(arr[8].toString()));

			entries.add(entr);
		}
		return entries;
		
		
	}

	@Override
	public List<Entry> getEntriesByUser(String username) {
		
		String queryStr = "SELECT e.id,e.comment,e.coordX,e.coordY,e.upVoteCount,e.downVoteCount,e.imageMeta,e.priority,u.username from Reason r  join EntryReason er on er.ReasonId = r.id join Entry e on er.EntryID = e.id  "
				+ "join User u on e.submittedBy = u.id where e.username = :username";
		Query query = sessionFactory.getCurrentSession().createSQLQuery(
				queryStr);
		query.setParameter("username", username);
		
		Iterator<Object[]> iter = query.list().iterator();

		List<Entry> entries = new ArrayList<Entry>();
		while (iter.hasNext()) {
			Object[] arr = iter.next();
			Entry entr = new Entry();
			entr.setId((Integer) arr[0]);
			entr.setComment((String) arr[1]);
			entr.setCoordX((BigDecimal) arr[2]);
			entr.setCoordY((BigDecimal) arr[3]);
			entr.setUpVoteCount((Integer) arr[4]);
			entr.setDownVoteCount((Integer) arr[5]);
			entr.setImageMeta((String) arr[6]);
			entr.setPriority((Integer) arr[7]);
			entr.setUser(new User(arr[8].toString()));

			entries.add(entr);
		}
		return entries;
	}

}
