package net.member.action;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import net.member.db.MemberBean;
import net.member.db.MemberDAO;

public class MemberUpdateAction implements Action {
	@Override
	public ActionForward execute(HttpServletRequest request, HttpServletResponse response) throws Exception {
		MemberDAO memberdao = new MemberDAO(); 
		MemberBean memberdata = new MemberBean(); 
		ActionForward forward = new ActionForward(); 
		HttpSession session = request.getSession();
		
		String id = (String) session.getAttribute("id"); 
		boolean result = false;
		
		try {
			memberdata.setM_ID(id);
			memberdata.setM_NAME(request.getParameter("M_NAME"));
			memberdata.setM_EMAIL(request.getParameter("M_EMAIL"));
			memberdata.setM_PHONE(request.getParameter("M_PHONE"));
			memberdata.setM_PASSWORD(request.getParameter("M_PASSWORD")); 
			
			result = memberdao.memberUpdate(memberdata);
			request.setAttribute("memberdetail",memberdata);
			System.out.println("업데이트액션:"+result);
			if(result == false) {
				return null;
			}
			forward.setRedirect(false);
			forward.setPath("./reknowkr/memberLogin/info.jsp");
			return forward;
		}catch(Exception e) {
			e.printStackTrace();
		}
		return null;
	}
}
